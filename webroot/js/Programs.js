$(function(){
	getPrograms();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Program');
        $('#programs-modal').modal('show');
    });
    $('#programs-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Program');
        $.ajax({
            url: BASE_URL + '/api/Programs/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                	$('#program-name').val(data.program_name);
                    $('#subsidy-type').val(data.subsidy_type);
                	$('#description').val(data.description);
                    $('#start-date').val(data.start_date);
                	$('#end-date').val(data.end_date);
                    $('#start-time').val(data.start_time);
                    $('#end-time').val(data.end_time);
                	$('#id').val(data.id);
                    $('#programs-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#programs-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Programs/add';
		}else{
			url = BASE_URL + '/api/Programs/edit/' + id;
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
				getPrograms();
				msgBox(data.status,data.message);
				$('#programs-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#programs-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Programs/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getPrograms();
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

    $('#programs-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#program_name').focus();
        }, 500);
    });

    $('#programs-modal').on('hidden.bs.modal', function() {
        $("#programs-form").trigger("reset");
        $("#id").val('');
    });
});

function getPrograms()
{
	$('#programs-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Programs/getPrograms'
        },
        "columns": [
			{data:"program_name"},
            {data:"subsidy_type"},
            {data:"description"},
            {data:"start_date"},
            {data:"end_date"},
            {data:"start_time"},
            {data:"end_time"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;"><a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit Program" data-id="'+ data.id +'"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete Program" data-id="'+ data.id +'"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                }
            }
        ]
	});
}