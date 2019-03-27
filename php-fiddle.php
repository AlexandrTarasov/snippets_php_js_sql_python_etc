function fibonacci($n, $i)
{
    if ($n < 3) {
		echo'>>>';echo($n);
        return 1; 
    }
    else {
		echo'='; echo($i); echo($n);
		echo'</br>';
        return fibonacci($n-1, '-') + fibonacci($n-2, '*');
    }
}


echo (fibonacci(7,'-'));

echo("...\n");
