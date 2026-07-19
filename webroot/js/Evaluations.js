$(function(){
	getEvaluations();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Evaluation');
        $('#evaluations-modal').modal('show');
    });
    $('#evaluations-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Evaluation');
        $.ajax({
            url: BASE_URL + '/api/Evaluations/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                	$('#farm_size').val(data.farm_size);
                	$('#crop_yield_before').val(data.crop_yield_before);
                	$('#crop_yield_after').val(data.crop_yield_after);
                	$('#income_before').val(data.income_before);
                	$('#income_after').val(data.income_after);
                	$('#pest').val(data.pest);
                	$('#calamity').val(data.calamity);
                	$('#effectiveness_label').val(data.effectiveness_label);
                	$('#id').val(data.id);
                    $('#evaluations-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#evaluations-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Evaluations/add';
		}else{
			url = BASE_URL + '/api/Evaluations/edit/' + id;
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
				getEvaluations();
				msgBox(data.status,data.message);
				$('#evaluations-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#evaluations-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Evaluations/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getEvaluations();
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

    $('#evaluations-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#farm_size').focus();
        }, 500);
    });

    $('#evaluations-modal').on('hidden.bs.modal', function() {
        $("#evaluations-form").trigger("reset");
        $("#id").val('');
    });
});

function getEvaluations()
{
	$('#evaluations-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Evaluations/getEvaluations'
        },
        "columns": [
			{data:"farm_size"},
            {data:"crop_yield_before"},
            {data:"crop_yield_after"},
            {data:"income_before"},
            {data:"income_after"},
            {data:"pest"},
            {data:"calamity"},
            {data:"effectiveness_label"},
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