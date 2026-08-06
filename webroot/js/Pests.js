$(function(){
	getPests();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Pest');
        $('#pests-modal').modal('show');
    });
    $('#pests-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Pest');
        $.ajax({
            url: BASE_URL + '/api/Pests/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                	$('#pest-name').val(data.pest_name);
                	$('#description').val(data.description);
                	$('#id').val(data.id);
                    $('#pests-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#pests-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Pests/add';
		}else{
			url = BASE_URL + '/api/Pests/edit/' + id;
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
				getPests();
				msgBox(data.status,data.message);
				$('#pests-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#pests-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Pests/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getPests();
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

    $('#pests-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#pest_name').focus();
        }, 500);
    });

    $('#pests-modal').on('hidden.bs.modal', function() {
        $("#pests-form").trigger("reset");
        $("#id").val('');
    });
});

function getPests()
{
	$('#pests-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Pests/getPests'
        },
        "columns": [
			{data:"pest_name"},
            {data:"description"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;"><a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit Personnel" data-id="'+ data.id +'"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete Personnel" data-id="'+ data.id +'"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                }
            }
        ]
	});
}