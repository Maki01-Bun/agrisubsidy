$(function(){
	getDistributions();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Distribution');
        $('#distributions-modal').modal('show');
    });
    $('#distributions-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Distribution');
        $.ajax({
            url: BASE_URL + '/api/Distributions/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                    $('#farmer_id').val(data.farmer_id);
                	$('#subsidy_item').val(data.subsidy_item);
                	$('#quantity').val(data.quantity);
                    $('#distribution_date').val(data.distribution_date);
                    $('#received_date').val(data.received_date);
                    $('#status').val(data.status);
                	$('#id').val(data.id);
                    $('#distributions-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#distributions-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Distributions/add';
		}else{
			url = BASE_URL + '/api/Distributions/edit/' + id;
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
				getDistributions();
				msgBox(data.status,data.message);
				$('#users-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#distributions-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Distributions/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getDistributions();
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

    $('#distributions-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#subsidy_item').focus();
        }, 500);
    });

    $('#distributions-modal').on('hidden.bs.modal', function() {
        $("#distributions-form").trigger("reset");
        $("#id").val('');
    });
});

function getDistributions()
{
	$('#distributions-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Distributions/getDistributions'
        },
        "columns": [
            {data:"farmer_id"},
			{data:"subsidy_item"},
            {data:"quantity"},
            {data:"distribution_date"},
            {data:"received_date"},
            {data:"status"},
            { data: null,render: function(data){
                    var option = '<div style="text-align:center;"><a href="" class="edit" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Edit Distribution" data-id="'+ data.id +'"><i' +
                        ' class="fa fas fa-pen"></i></a> | <a href="" class="delete text-danger" data-toggle="tooltip" + ' +
                        'data-placement="bottom" title="Delete Distribution" data-id="'+ data.id +'"><i' +
                        ' class="fa fa fa-trash"></i></a></div>';
                    return option;
                }
            }
        ]
	});
}