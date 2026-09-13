$(function(){
	getSchedules();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Schedule');
        $('#schedules-modal').modal('show');
    });
    $('#schedules-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Schedule');
        $.ajax({
            url: BASE_URL + '/api/Schedules/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                	$('#program-code').val(data.program_code);
                    $('#program-name').val(data.program_name);
                	$('#description').val(data.description);
                    $('#barangay').val(data.barangay);
                    $('#start-date').val(data.start_date);
                	$('#end-date').val(data.end_date);
                    $('#start-time').val(data.start_time);
                    $('#end-time').val(data.end_time);
                	$('#id').val(data.id);
                    $('#schedules-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#schedules-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Schedules/add';
		}else{
			url = BASE_URL + '/api/Schedules/edit/' + id;
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
				getSchedules();
				msgBox(data.status,data.message);
				$('#schedules-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#schedules-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Schedules/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getSchedules();
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

    $('#schedules-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#program_name').focus();
        }, 500);
    });

    $('#schedules-modal').on('hidden.bs.modal', function() {
        $("#schedules-form").trigger("reset");
        $("#id").val('');
    });
});

function getSchedules()
{
	$('#schedules-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Schedules/getSchedules'
        },
        "columns": [
            {data:"program_code"},
			{data:"program_name"},
            {data:"description"},
            {data:"barangay"},
            {data:"start_date"},
            {data:"end_date"},
            {data:"start_time"},
            {data:"end_time"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;"><a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit Schedule" data-id="'+ data.id +'"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete Schedule" data-id="'+ data.id +'"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                }
            }
        ]
	});
}