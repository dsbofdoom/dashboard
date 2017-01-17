function isMail(mailField){
  strMail = mailField.value;
  var re = new RegExp;
  re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  var arr = re.exec(strMail);
  if (arr == null)
    return(false);
  else
    return(true);
}

function minLen(txtField, minVal){
  strExp = txtField.value;
  l = strExp.length;
  if (l < minVal)
    return(true);
  else
    return(false);
}

function maxLen(txtField, maxVal){
  strExp = txtField.value;
  l = strExp.length;
  if (l > maxVal)
    return(true);
  else
    return(false);
}

function isBlank(txtField){
  if (txtField.value)
    return (false);
  else
    return(true);
}

function isSelectedZero(txtField){
  selected = txtField.selectedIndex;
  if (selected == 0)
    return(true);
  else
    return(false);
}

function isNumber(txtField){
  numExp = txtField.value;
  if (isNaN(numExp) || (numExp.length == 0))
    return (false);
  else
    return(true);
}

function isCPF(txtField){ 

  var i; 
  s = txtField.value;  
  var c = s.substr(0,9); 
  var dv = s.substr(9,2); 
  var d1 = 0; 
  
  for (i = 0; i < 9; i++){ 
    d1 += c.charAt(i)*(10-i); 
  } 
  
  if (d1 == 0) return false;   
  
  d1 = 11 - (d1 % 11); 
  
  if (d1 > 9) d1 = 0; 
  
  if (dv.charAt(0) != d1) return false; 
  
  d1 *= 2; 
  
  for (i = 0; i < 9; i++){ 
    d1 += c.charAt(i)*(11-i);   
  } 
  
  d1 = 11 - (d1 % 11); 
  
  if (d1 > 9) d1 = 0; 
  
  if (dv.charAt(1) != d1) return false; 
  
  return true; 
  
}

function MascaraData(txtField)
{
	texto = txtField.value;
	qtde = texto.length;
	if ((qtde == 2) || (qtde == 5))
		txtField.value = texto+"/";
	return true;
}

function MascaraHora(txtField)
{
	texto = txtField.value;
	qtde = texto.length;
	if (qtde == 2)
		txtField.value = texto+":";
	return true;
}

function MascaraCPF(txtField)
{
	texto = txtField.value;
	qtde = texto.length;
	if ((qtde == 3) || (qtde == 7))
		txtField.value = texto+".";
	if (qtde == 11)
		txtField.value = texto+"-";	
	return true;
}

function MascaraCEP(txtField)
{
	texto = txtField.value;
	qtde = texto.length;
	if (qtde == 5)
		txtField.value = texto+"-";	
	return true;
}


function UpperCampo(txtField)
{
	txtField.value = txtField.value.toUpperCase();
	return true;
}


function PassaCodigo(txtField, txtList, tipo)
{
	if (tipo == 1)
	{
		var contador = txtList.length;
		txtList.options[0].selected = true;
		for (xCont=0;xCont<contador;xCont++)
		{
			if (txtList.options[xCont].value == txtField.value)
				txtList.options[xCont].selected = true;
		}		
	}
	else
	{
		txtField.value = "";
		txtField.value = txtList.options[txtList.selectedIndex].value;
	}
	
	return true;
}

function Confirma(strMsg)
{
	if (confirm("Confirma a Operação de "+strMsg+"?")){
		return true
	}else{
		return false
	}
}