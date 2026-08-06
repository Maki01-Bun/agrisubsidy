$(function(){
    getFarmers();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Farmer');
        $('#farmers-modal').modal('show');
    });
    $('#farmers-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Farmer');
        $.ajax({
            url: BASE_URL + '/api/Farmers/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                    $('#farmer-no').val(data.farmer_no);
                    $('#first-name').val(data.first_name);
                    $('#last-name').val(data.last_name);
                    $('#middle-name').val(data.middle_name);
                    $('#birthdate').val(data.birthdate);
                    $('#gender').val(data.gender);
                    $('#address').val(data.address);
                    $('#contact-no').val(data.contact_no);
                    $('#created').val(data.created);
                    $('#modified').val(data.modified);
                    $('#user_id').val(data.user_id);
                    $('#id').val(data.id);
                    $('#farmers-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

    $('#farmers-form').submit(function(e){
        e.preventDefault();
        let fd =new FormData(this);
        let id = $('#id').val();
        let url = '';
        if(id==''){
            url = BASE_URL + '/api/Farmers/add';
        }else{
            url = BASE_URL + '/api/Farmers/edit/' + id;
        }

        $.ajax({
            processData:false,
            contentType:false,
            data:fd,
            url:url,
            type:'POST',
            dataType:'json'
        }).done(function(data){
            if(data.status=='success'){
                getFarmers();
                msgBox(data.status,data.message);
                $('#farmers-modal').modal('hide');
            }else{
                msgBox(data.status,data.message);
            }
        }).fail(function(jqXHR,textStatus,errorThrown){
            msgBox('error',errorThrown);
        });
    });

    $('#farmers-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Farmers/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getFarmers();
                            msgBox(data.status,data.message);
                        }else{
                            msgBox(data.status,data.message);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown){
                        msgBox('error',errorThrown);
                    });
            }
        });
    });

    $('#farmers-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farmer_no').focus();
        }, 500);
    });

    $('#farmers-modal').on('hidden.bs.modal', function() {
        $("#farmers-form").trigger("reset");
        $("#id").val('');
    });
});

function getFarmers()
{
    $('#farmers-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Farmers/getFarmers'
        },
        "columns": [
            {data:"farmer_no"},
            {data:"first_name"},
            {data:"last_name"},
            {data:"middle_name"},
            {data:"birthdate"},
            {data:"gender"},
            {data:"address"},
            {data:"contact_no"},
            {data:"created"},
            {data:"modified"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;"><a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit Farmers" data-id="'+ data.id +'"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete Farmers" data-id="'+ data.id +'"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                }
            }
        ]
    });
}