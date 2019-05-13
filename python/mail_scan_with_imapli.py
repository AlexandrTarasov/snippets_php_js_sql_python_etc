#!/usr/bin/env python
# -*- coding: utf-8 -*-

import imaplib, email, os
import quopri
import sys
from email.header import decode_header

# user = 'autox@gmail.com'
# passrd = '111111111'
# imap_url = 'imap.gmail.com'
user = 'laddadd@ukr.net'
passrd = '22222222'
imap_url = 'imap.ukr.net'

attachment_dir = sys.path[0]
todays_date = (datetime.date.today() - datetime.timedelta(1)).strftime("%d-%b-%Y")

def get_attachments(msg):
    for part in msg.walk():
        if part.get_content_maintype() == 'multipart':
            continue
        if part.get('Content-Disposition') is None:
            continue
        file_durty = part.get_filename()
        name_and_charcode_list = decode_header(file_durty)
        
        if False == file_name_chack(name_and_charcode_list): 
            continue
        else:
            fileName = file_name_chack(name_and_charcode_list)
        if bool(fileName):
            filePath = os.path.join(attachment_dir, fileName)
            with open(filePath,'wb') as f:
                f.write(part.get_payload(decode=True))
    return True

def file_name_chack(name_and_charcode):
    if name_and_charcode[0][1] == None:
        name = name_and_charcode[0][0]
    else:
        name = (name_and_charcode[0][0].decode(name_and_charcode[0][1]))

    if (".xls" in name) or (".xlsx" in name):
        return name
    else:
        return False
            

def get_body(msg):
    if msg.is_multipart():
        return get_body(msg.get_payload(0))
    else:
        return msg.get_payload(None, True)

def search(key, value, date, con):
    result, data  = con.search(None, '(SINCE "'+date+'")' )
    # result, data  = con.search(None, key, '"()"'.format(value))
    return data

def get_emails(result_bytes):
    msgs = []
    for num in result_bytes[0].split():
        typ, data = con.fetch(num, '(RFC822)')
        msgs.append(data)
    return msgs 

con = imaplib.IMAP4_SSL(imap_url)
con.login(user,passrd)

con.select('gp-prices')
# result, data = con.fetch(b'1','(RFC822)')
# raw = email.message_from_bytes(data[0][1])

raw_msg_in_bit = search('FROM', 'gmail.com', todays_date, con)

list_of_msgs = get_emails(raw_msg_in_bit)

for msg in list_of_msgs:
    # print(get_body(email.message_from_bytes(msg[0][1])))
    get_attachments(email.message_from_bytes(msg[0][1]))
# print(get_body(raw).decode('utf-8'))
