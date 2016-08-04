/******************************************************************************
 * Funções de Validação de CPF e CNPJ
 * Método de uso - CPF: <input type="text" id="cpf" onblur="confereCPF()" />
 * Método de uso - CNPJ: <input type="text" id="cnpj" onblur="confereCNPJ()" />
 *
/******************************************************************************
 * VALIDAÇÃO DE CPF
 *****************************************************************************/
function valida_cpf(cpf){
  cpf = cpf.replace('.','');
  cpf = cpf.replace('-','');
  var numeros, digitos, soma, i, resultado, digitos_iguais;
  digitos_iguais = 1;
  if (cpf.length < 11)
        return false;
  for (i = 0; i < cpf.length - 1; i++)
        if (cpf.charAt(i) != cpf.charAt(i + 1))
              {
              digitos_iguais = 0;
              break;
              }
  if (!digitos_iguais)
        {
        numeros = cpf.substring(0,9);
        digitos = cpf.substring(9);
        soma = 0;
        for (i = 10; i > 1; i--)
              soma += numeros.charAt(10 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
              return false;
        numeros = cpf.substring(0,10);
        soma = 0;
        for (i = 11; i > 1; i--)
              soma += numeros.charAt(11 - i) * i;
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
              return false;
        return true;
        }
  else
        return false;
}

function confereCPF(){
   
  var cpf = document.getElementById('cpf').value;
  
  cpf = cpf.replace('.','');
  cpf = cpf.replace('-','');
    
  
  if(valida_cpf(cpf)){
    //document.getElementById('msgOK').style.display = 'none'; 
  }else{
    
    $('#cpf').val('');
    document.getElementById('cpf').autofocus;
    alert('CPF Inválido.'); 
  }  
  
}

/******************************************************************************
 * VALIDAÇÃO DE CNPJ
 *****************************************************************************/
function validaCnpj(str){
    str = str.replace('.','');
    str = str.replace('.','');
    str = str.replace('.','');
    str = str.replace('-','');
    str = str.replace('/','');
    cnpj = str;
    var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
    digitos_iguais = 1;
    if (cnpj.length < 14 && cnpj.length < 15)
        return false;
    for (i = 0; i < cnpj.length - 1; i++)
        if (cnpj.charAt(i) != cnpj.charAt(i + 1))
    {
        digitos_iguais = 0;
        break;
    }
    if (!digitos_iguais)
    {
        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--)
        {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
            return false;
        return true;
    }
    else
        return false;
}

function confereCNPJ(){
    
  
  
  var cnpj = document.getElementById('cnpj').value;
  
  cnpj = cnpj.replace('.','');
  cnpj = cnpj.replace('-','');          
  
  if(validaCnpj(cnpj)){    
    $('.msg_cnpj').html('');
  }else{
    
    $('#cnpj').val('');
    document.getElementById('cnpj').autofocus;
    //alert('CNPJ Inválido.'); 
    $('.msg_cnpj').html('<span class="text text-danger">CNPJ Inválido</span>');
  }  
  
}