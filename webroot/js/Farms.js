$(function(){
	getFarms();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Farm');
        $('#farms-modal').modal('show');
    });
    $('#farms-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Farm');
        $.ajax({
            url: BASE_URL + '/api/Farms/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                    $('#farmer-id').val(data.farmer_id);
                	$('#farm-name').val(data.farm_name);
                	$('#farm-size').val(data.farm_size);
                    $('#location').val(data.location);
                    $('#average-yield').val(data.average_yield);
                	$('#id').val(data.id);
                    $('#farms-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#farms-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Farms/add';
		}else{
			url = BASE_URL + '/api/Farms/edit/' + id;
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
				getFarms();
				msgBox(data.status,data.message);
				$('#farms-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#farms-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Farms/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getFarms();
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

    $('#farms-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farm-name').focus();
        }, 500);
    });

    $('#farms-modal').on('hidden.bs.modal', function() {
        $("#farms-form").trigger("reset");
        $("#id").val('');
    });
});

function getFarms()
{
	$('#farms-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Farms/getFarms',
        },
        "columns": [
            {data:"farmer_name"},
			{data:"farm_name"},
            {data:"farm_size"},
            {data:"location"},
            {data:"average_yield"},
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