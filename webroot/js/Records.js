$(function(){
	getRecords();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Record');
        $('#records-modal').modal('show');
    });
    $('#records-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Record');
        $.ajax({
            url: BASE_URL + '/api/Records/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                	$('#farmer-id').val(data.farmer_id);
                    $('#program_name').val(data.program_name);
                	$('#subsidy-item').val(data.subsidy_item);
                	$('#quantity').val(data.quantity);
                    $('#distribution-date').val(data.distribution_date);
                    $('#received-date').val(data.received_date);
                    $('#status').val(data.status);
                	$('#id').val(data.id);
                    $('#records-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#records-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Records/add';
		}else{
			url = BASE_URL + '/api/Records/edit/' + id;
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
				getRecords();
				msgBox(data.status,data.message);
				$('#records-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#records-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Records/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getRecords();
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

    $('#records-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farmer_id').focus();
        }, 500);
    });

    $('#records-modal').on('hidden.bs.modal', function() {
        $("#records-form").trigger("reset");
        $("#id").val('');
    });
});

function getRecords()
{
	$('#records-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Records/getRecords'
        },
        "columns": [
			{data:"farmer_name"},
            {data:"program_name"},
			{data:"subsidy_item"},
            {data:"quantity"},
            {data:"distribution_date"},
            {data:"received_date"},
            {data:"status"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;"><a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit Record" data-id="'+ data.id +'"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete Record" data-id="'+ data.id +'"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                }
            }
        ]
	});
}