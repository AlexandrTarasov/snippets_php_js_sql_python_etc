function main() 
{
  var BAD_CODES = [404,500];
  var HTTP_OPTIONS = { muteHttpExceptions:true};
  var TO = ['zinchenko.sotnik@gmail.com','sotnik.developer@gmail.com'];
  var SUBJECT = 'Р‘РёС‚С‹Рµ Url '+_getDateString();
  var already_checked = {}; 
  var bad_entities = [];
  //
 var accountIterator = MccApp.accounts()
      .withCondition("LabelNames CONTAINS '" + 'Work' + "'")
      .get();
  //С†РёРєР» РІС‹РІРѕРґРё Р°РЅС„РѕСЂРјР°С†РёРё
   while (accountIterator.hasNext()) 
   {     
    var account = accountIterator.next();
    // Select the client account.
    MccApp.select(account);
    //******************************************//
      //Let's look at ads and keywords for urls
       var iters = [
      //For Ad Level Urls
      AdWordsApp.ads()
      .withCondition("Status = 'ENABLED'")
      .withCondition("AdGroupStatus = 'ENABLED'") //
      .withCondition("CampaignStatus = 'ENABLED'")
    //  .withCondition("Type = 'TEXT_AD'")
      .get(),
      //For Keyword Level Urls
      AdWordsApp.keywords()
      .withCondition("Status = 'ENABLED'")
      .withCondition("DestinationUrl != ''")
      .withCondition("AdGroupStatus = 'ENABLED'")
      .withCondition("CampaignStatus = 'ENABLED'")
      .get()
      ];
     
     for(var x in iters) 
     {
       var iter = iters[x];
        while( iter.hasNext() )
        {
          var entity = iter.next();
          if( entity.urls().getFinalUrl() == null) { continue; }
          var url = entity.urls().getFinalUrl();
          if(url.indexOf('{') >= 0) {
           //Let's remove the value track parameters
           url = url.replace(/\{[0-9a-zA-Z]+\}/g,'');
          }
          if(already_checked[url]) { continue; }
          var response_code;
          try {
            //Logger.log("Testing url: "+url);
            response_code = UrlFetchApp.fetch(url, HTTP_OPTIONS).getResponseCode();
          } catch(e) {
            //Something is wrong here, we should know about it.
            bad_entities.push({e : entity, code : -1, name : account.getName()});
          }
          if(BAD_CODES.indexOf(response_code) >= 0) {
           //This entity has an issue.  Save it for later. 
            bad_entities.push({e : entity, code : response_code, name : account.getName()});
          }else{ already_checked[url] = true; }
          Logger.log("Testing url: "+url+"  Code: "+response_code);
          
        }
     }
   }
  
      //******************************************//
     var column_names = ['Account','Type','CampaignName','AdGroupName','Id','Headline/KeywordText','ResponseCode','DestUrl'];
     var attachment = column_names.join(",")+"\n";
     for(var i in bad_entities) {
     attachment += _formatResults(bad_entities[i],",");
     }
     if(bad_entities.length > 0) 
     {
      var options = { attachments: [Utilities.newBlob(attachment, 'text/csv', 'Р±РёС‚С‹Рµ_СЃСЃС‹Р»РєРё_'+_getDateString()+'.csv')] };
      var email_body = "Р‘РёС‚С‹С… СЃСЃС‹Р»РѕРє " + bad_entities.length + ". Р’СЃРµ Р±РёС‚С‹Рµ СЃСЃС‹Р»РєРё РІ attachment.";
      
      for(var i in TO) {
       MailApp.sendEmail(TO[i], SUBJECT, email_body, options);
      }
     }  
    //******************************************// 
}

//Formats a row of results separated by SEP
function _formatResults(entity,SEP) {
  var e = entity.e;
  if(typeof(e['getHeadline']) != "undefined") {
    //this is an ad entity
    return [entity.name,
            "Ad",
            e.getCampaign().getName(),
            e.getAdGroup().getName(),
            e.getId(),
            e.getHeadline(),
            entity.code,
            e.urls().getFinalUrl(),
           ].join(SEP)+"\n";
  } else {
    // and this is a keyword
    return [entity.name,
            "Keyword",
            e.getCampaign().getName(),
            e.getAdGroup().getName(),
            e.getId(),
            e.getText(),
            entity.code,
            e.urls().getFinalUrl(),
           ].join(SEP)+"\n";
  }
}
//Helper function to format todays date
function _getDateString() {
  return Utilities.formatDate((new Date()), AdWordsApp.currentAccount().getTimeZone(), "yyyy-MM-dd");
}
