document.querySelector('#send').addEventListener('click', function(){

    let telefono = document.querySelector('#telefono').value;
    let observaciones = document.querySelector('#observaciones').value;
    let url = "https://api.whatsapp.com/send?phone="+telefono+"&text="+observaciones;    

    window.open(url);
})