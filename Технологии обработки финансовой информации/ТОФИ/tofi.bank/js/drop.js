$(document).ready(function() {
    
    var dropZone = $('#dropZone'),
        maxFileSize = 100000000; // максимальный размер фалйа - 1 мб.
    
    // Проверка поддержки браузером
    if (typeof(window.FileReader) == 'undefined') {
        dropZone.text('Не поддерживается браузером!');
        dropZone.addClass('error');
    }
    
    // Добавляем класс hover при наведении
    dropZone[0].ondragover = function() {
        dropZone.addClass('hover');
        return false;
    };
    
    // Убираем класс hover
    dropZone[0].ondragleave = function() {
        dropZone.removeClass('hover');
        return false;
    };
    
    // Обрабатываем событие Drop
    dropZone[0].ondrop = function(event) {
        event.preventDefault();
        dropZone.removeClass('hover');

        // Создаем запрос
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', uploadProgress, false);
        xhr.onreadystatechange = stateChange;
        xhr.open('POST', $('#form-up').attr('action'));
//        xhr.setRequestHeader('X-FILE-NAME', file.name);
//        xhr.send(file);

        if(event.dataTransfer.files.length) {

            var fd = new FormData
        
            for(var i = 0; i < event.dataTransfer.files.length; i++) {

                var file = event.dataTransfer.files[i];

                // Проверяем размер файла
                if (file.size > maxFileSize) {
                    dropZone.text('Файл слишком большой!');
                    dropZone.addClass('error');
                    return false;
                }

                 fd.append("file" + i, file)
            }
            xhr.send(fd);
        }
    };
    
    // Показываем процент загрузки
    function uploadProgress(event) {
        var percent = parseInt(event.loaded / event.total * 100);
        dropZone.text('Загрузка: ' + percent + '%');
    }
    
    // Пост обрабочик
    function stateChange(event) {
        if (event.target.readyState == 4) {
            if (event.target.status == 200) {
                dropZone.text('Загрузка успешно завершена!');
                dropZone.addClass('drop');
                setTimeout("$('#dropZone').removeClass('drop'); $('#dropZone').text('Для загрузки перетащите файлы сюда.');", 5000)
                DocumentUpload.reload($('#document_type').val(), $('#document_id').val());
            } else {
                dropZone.text('Произошла ошибка!');
                dropZone.addClass('error');
                setTimeout("$('#dropZone').removeClass('error'); $('#dropZone').text('Для загрузки перетащите файлы сюда.');", 5000)
            }
        }
    }
});

var DocumentUpload = {
    reload: function(document_type, document_id) {

		var params = {
			operation: 'reload',
			document_type: document_type,
            document_id: document_id
		};
        
        $.get('/admin/upload.php', params, function(jsonResult){
            if(!jsonResult.success) {
                alert('Произошла ошибка при возврате ajax-ом списка файлов.');
                return;
            }

            if(jsonResult.files.length) {

                var html = '';
                for(var i = 0; i < jsonResult.files.length; i++) {
                    html += '<div><a target="_blank" href="/documents/'+document_type+'-'+document_id+'/'+jsonResult.files[i]+'">'+jsonResult.files[i]+'</a> <a style="color:red; cursor:pointer;" onclick="DocumentUpload.removeFile(\''+document_type+'\', \''+document_id+'\', \''+jsonResult.files[i]+'\')">удалить</a></div>';
                }
                $('#uploaded-files-list').html(html);
                $('#uploaded-files-list').show();
            }
            else {
                $('#uploaded-files-list').html('');
                $('#uploaded-files-list').hide();
            }
        }, 'json');
    },
    removeFile: function(document_type, document_id, file_name) {
        if(confirm('Точно удалить файл?')) {

            var params = {
                operation: 'delete',
                document_type: document_type,
                document_id: document_id,
                file_name: file_name
            };

            $.get('/admin/upload.php', params, function(jsonResult){
                if(!jsonResult.success) {
                    alert('Произошла ошибка при удалении файла.');
                    return;
                }

                DocumentUpload.reload(document_type, document_id);
            }, 'json');
        }
    }
}