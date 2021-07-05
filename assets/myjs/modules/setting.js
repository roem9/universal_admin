$(".editWebsite").click(function(){
    let result = ajax(url_base+"home/getSetting");

    let form = "#editWebsite";
    $(form+" [name='web_admin']").val(result.web_admin.value);
    $(form+" [name='web_peserta']").val(result.web_peserta.value);
})

$("#editWebsite .btnEdit").click(function(){
    Swal.fire({
        icon: 'question',
        text: 'Yakin akan merubah data setting?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then(function (result) {
        if (result.value) {
            let form = "#editWebsite";
            let web_admin = $(form+" [name='web_admin']").val();
            let web_peserta = $(form+" [name='web_peserta']").val();
            
            let eror = required(form);
            
            if( eror == 1){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'lengkapi isi form terlebih dahulu'
                })
            } else {
                data = {web_admin: web_admin, web_peserta: web_peserta}
                let result = ajax(url_base+"home/edit_setting", "POST", data);

                if(result == 1){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        text: 'Berhasil merubah data setting',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'terjadi kesalahan'
                    })
                }
            }
        }
    })
})

$(".editListening").click(function(){
    let result = ajax(url_base+"home/get_poin/listening");
    
    form = "#editPoin"
    $(form+" .modal-title").html("Poin Listening")

    html = "";
    result.forEach((data, i) => {
        html += `
        <div class="form-floating mb-1">
            <input type="hidden" name="id[]" class="form" value="`+data.id+`">
            <input type="text" name="poin[]" class="form form-control required" value="`+data.poin+`">
            <label for="">Benar `+i+`</label>
        </div>`
    });

    $(form+" .poin").html(html);
})

$(".editStructure").click(function(){
    let result = ajax(url_base+"home/get_poin/structure");
    
    form = "#editPoin"
    $(form+" .modal-title").html("Poin Structure")

    html = "";
    result.forEach((data, i) => {
        html += `
        <div class="form-floating mb-1">
            <input type="hidden" name="id[]" class="form" value="`+data.id+`">
            <input type="text" name="poin[]" class="form form-control required" value="`+data.poin+`">
            <label for="">Benar `+i+`</label>
        </div>`
    });

    $(form+" .poin").html(html);
})

$(".editReading").click(function(){
    let result = ajax(url_base+"home/get_poin/reading");
    
    form = "#editPoin"
    $(form+" .modal-title").html("Poin Reading")

    html = "";
    result.forEach((data, i) => {
        html += `
        <div class="form-floating mb-1">
            <input type="hidden" name="id[]" class="form" value="`+data.id+`">
            <input type="text" name="poin[]" class="form form-control required" value="`+data.poin+`">
            <label for="">Benar `+i+`</label>
        </div>`
    });

    $(form+" .poin").html(html);
})

$("#editPoin .btnEdit").click(function(){
    Swal.fire({
        icon: 'question',
        text: 'Yakin akan merubah poin?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then(function (result) {
        if (result.value) {
            let form = "#editPoin";

            let eror = required(form);
            
            if( eror == 1){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'lengkapi isi form terlebih dahulu'
                })
            } else {
                let id = [];
                $(form+" [name='id[]']").each(function() {
                    id.push($(this).val());
                });
                
                let poin = [];
                $(form+" [name='poin[]']").each(function() {
                    poin.push($(this).val());
                });

                data = {id: id, poin: poin}
                let result = ajax(url_base+"home/edit_poin", "POST", data);

                if(result == 1){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        text: 'Berhasil merubah data setting',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'terjadi kesalahan'
                    })
                }
            }
        }
    })
})

$(document).on("click", ".editLogo", function(){
    let form = "#editLogo";

    html = `
    <div class="d-block mb-3">
        <img src="`+url_base+`assets/img/logo.png?t=`+Math.random()+`" onerror="this.onerror=null; this.src='`+url_base+`assets/tabler-icons-1.39.1/icons/x.svg'" class="card-img-top" width=100%>
    </div>`;

    $(form+" .gallery").html(html)
})

$("#editLogo .btnUpload").click(function(){
    let form = "#editLogo";

    var fd = new FormData();
    var files = $(form+' [name="file"]')[0].files;
    
    // Check file selected or not
    if(files.length > 0 ){
        Swal.fire({
            icon: 'question',
            text: 'Yakin akan mengubah logo?',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(function (result) {
            if (result.value) {
                fd.append('file',files[0]);

                let eror = required(form);

                loading();

                if( eror == 1){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'lengkapi isi form terlebih dahulu'
                    })
                } else {
                    $.ajax({
                        url: url_base+'home/upload_logo',
                        type: 'post',
                        //   data: {fd, nama_audio:nama_audio},
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response){

                            if(response == 1){
                                $(form).modal("hide");
                                $(form+" .myform").trigger("reset");

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    text: 'Berhasil mengupload logo',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            } else if(response == 2){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    text: 'Gagal mengupload file. Format file harus PNG',
                                    // showConfirmButton: false,
                                    // timer: 1500
                                })
                            } else if(response == 0){
                                Swal.fire({
                                    position: 'center',
                                    icon: 'error',
                                    text: 'Gagal mengupload file',
                                    // showConfirmButton: false,
                                    // timer: 1500
                                })
                            }

                            // location.reload();
                            window.location.reload(false); 

                        },
                    });
                }
            }
        })
    }else{
        Swal.fire({
            position: 'center',
            icon: 'error',
            text: 'Pilih file terlebih dahulu',
            showConfirmButton: false,
            timer: 1500
        })
    }
});