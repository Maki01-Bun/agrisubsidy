$(function(){
	getFeedbacks();

    $('#add').on('click',function (e) {
        e.preventDefault();
        $('.modal-title').html('Add Feedback');
        $('#feedbacks-modal').modal('show');
    });
    $('#feedbacks-table').on('click','.edit',function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('.modal-title').html('Update Feedback');
        $.ajax({
            url: BASE_URL + '/api/Feedbacks/edit/' + id,
            type: "GET",
            dataType: 'json'
        })
            .done(function(data){
                if(data!=''){
                    $('#rating').val(data.rating);
                    $('#comment').val(data.comment);
                	$('#id').val(data.id);
                    $('#feedbacks-modal').modal('show');
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                msgBox('error',errorThrown);
            });
    });

	$('#feedbacks-form').submit(function(e){
		e.preventDefault();
		let fd =new FormData(this);
		let id = $('#id').val();
		let url = '';
		if(id==''){
			url = BASE_URL + '/api/Feedbacks/survey';
		}else{
			url = BASE_URL + '/api/Feedbacks/edit/' + id;
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
				getFeedbacks();
				msgBox(data.status,data.message);
				$('#feedbacks-modal').modal('hide');
			}else{
				msgBox(data.status,data.message);
			}
		}).fail(function(jqXHR,textStatus,errorThrown){
			msgBox('error',errorThrown);
		});
	});

    $('#feedbacks-table').on('click', '.delete', function (e) {
        e.preventDefault();
        let id=$(this).data('id');
        isDelete(function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: BASE_URL + '/api/Feedbacks/delete/'+ id,
                    type: "DELETE",
                    dataType: 'json',
                    headers : {
                        'X-CSRF-Token': $('[name="_csrfToken"]').val()
                    },
                })
                    .done(function(data, textStatus, jqXHR){
                        if(data.status=='success'){
                            getFeedbacks();
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

    $('#feedbacks-modal').on('shown.bs.modal', function() {
        setTimeout(function() {
            $('#rating').focus();
        }, 500);
    });

    $('#feedbacks-modal').on('hidden.bs.modal', function() {
        $("#feedbacks-form").trigger("reset");
        $("#id").val('');
    });
});

function getFeedbacks()
{
	$('#feedbacks-table').DataTable({
        "responsive": true,
        "destroy":true,
        "order": [[ 0, "asc" ]],
        "ajax": {
            "url": BASE_URL + '/api/Feedbacks/getFeedbacks'
        },
        "columns": [
            {data:"rating"},
            {data:"comment"}
        ]
	});
}