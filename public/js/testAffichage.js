//- - - - - - -  T E S T   J A V A S C R I P T - - - - - - - - //

//var Text = [];
var i = -1;
var elem= [[]];

var lis = document.getElementById("liste").getElementsByTagName("li");

for (var y =0; y<lis.length; y++)
{
	elem[y] = lis[y].innerHTML.split("\\");
}

//alert(elem[1][1]);



 
 function Affichagetext(text, img)
 {
 	document.getElementById('Dynamique-txt').innerHTML = text;
 	document.getElementById('Dynamique-img').innerHTML = img;
 	//document.getElementById('btn').remove();
 }


 function AffichageDyn()
 {
 		//i=4 ?  0 : i++;
 		if(++i>= lis.length )
 		{
 			i=0;
 		}
 		Affichagetext(elem[i][0],elem[i][1]);		
 }
 
 function Clear()
 {
	 	document.getElementById('Dynamique-txt').innerHTML = '';
	 	document.getElementById('Dynamique-img').innerHTML = '';
 }
 
 function ComboBox()
 {
	  //alert('click');
	  var x = document.createElement("SELECT");
	  x.setAttribute("id", "mySelect");
	  x.setAttribute("value", "tet");
	  document.getElementById('Dynamique').appendChild(x);
	  var z = document.createElement("option");
	  z.setAttribute("value", "article");
	  var t = document.createTextNode("Select an Article");

	  for(var i = 0; i<lis.length; i++)
	  {
		  var z = document.createElement("option");
		  z.setAttribute("value", "article");
		  var t = document.createTextNode(lis[i].innerHTML);
		  z.appendChild(t);
		  document.getElementById("mySelect").appendChild(z);
	  }

 
 }
 
 