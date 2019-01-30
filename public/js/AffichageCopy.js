//- - - - - - -  T E S T   J A V A S C R I P T - - - - - - - - //

//var Text = [];
var i = -1;


var lis = document.getElementById("liste").getElementsByTagName("li");

//var test = "Salut:Remi"
var elem = lis[0].innerHTML.split("\\");


alert(elem[1]);



 
 function Affichagetext(text)
 {
 	document.getElementById('Dynamique').innerHTML = text;
 	//document.getElementById('btn').remove();
 }


 function AffichageDyn()
 {
 		//i=4 ?  0 : i++;
 		if(++i>= lis.length )
 		{
 			i=0;
 		}
 		Affichagetext(lis[i].innerHTML);		
 }