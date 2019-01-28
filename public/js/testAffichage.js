//- - - - - - -  T E S T   J A V A S C R I P T - - - - - - - - //

var Text = [];

 Text[0] = '<p>AFFICHAGE 1 => OUAIS C\'EST COOL</p>';
 Text[1] = '<p>AFFICHAGE 2 => SALUT T\'ES BO !</p>';
 Text[2] = '<p>AFFICHAGE 3 => J\'AIME LES TESLA</p>';
 Text[3] = '<p>AFFICHAGE 4 => LES AUTO C\'EST MIEUX QUAND IL Y A DU JAKITUNING</p>';
 
 function Affichagetext(text)
 {
 	document.getElementById('Dynamique').innerHTML = text;
 }
 var i = -1;

 function AffichageDyn()
 {
 		i++;
 		if(i>=4)
 		{
 			i=0;
 		}
 		var texte = 'Text' + i.toString();
 		Affichagetext(Text[i]);		
 }