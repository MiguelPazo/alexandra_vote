$(document).ready(function () {
    $('#formLogin').submit(function (e) {
        e.preventDefault();
        
        var dataform = {};
        dataform.dni = $.trim($(this).find('#dni').val());       
        dataform.code = $.trim($(this).find('#code').val()); 
        
        if(dataform.dni == ''){
            alert('Debe ingresar su DNI'); 
            return false;                       
        }
        if(dataform.code == ''){
            alert('Debe ingresar su codigo UCE!'); 
            return false;             
        }   
        var codcheck = gen_validate(dataform.dni,dataform.code);               
        if (!dataform.code.endsWith(codcheck)){
            alert('El codigo UCE no es valido');  
            return false;            
        }
        //Validacion codigo        
        var data = {};
        data.dni = dataform.dni;
        data.code = Sha256.hash(dataform.dni+dataform.code);  
        data._token = $(this).find("input[name='_token']").val();              
        var url = $(this).attr('action');
        $.post(url, data, function (response) {
            if (response.success) {
                location.href = response.url;
            } else {
                alert(response.message)
            }
        });       
    });
});

function gen_validate(id,pwd)
{   
    pwd = pwd.substring(0,pwd.length-1);
    
    vid=id.trim()+pwd.trim();
    vtotal=0;
    for(i=0;i<vid.length;i++){
        item=vid.substr(i, 1);
        if (!isNumeric(item))
        {
            item=vid.substr(i, 1).charCodeAt();
        }
        vtotal=(vtotal+item*(i+1))%10;
    }
    vnumber=Number(vtotal.toString().substr(-1));
    return String.fromCharCode(vnumber+65);
    // return 'A';
}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}
