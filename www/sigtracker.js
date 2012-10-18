function Init()
{


}

function ___sigtypeInit(container)
{
    // target_container: #container_sig_type
    // параметры не требуются, функция заполняет селект НОВОГО сигнала
    // А вот хрен! Надо функции нормально писать, последовательно! По логике. Не будут появлятся лишние.
    $.post('sigtracker.ajax.php',{getdata: 'sig_types'},function(data)
        {   // callback
            $data = eval(data);
            $('select[name='+container+'] option').remove();
            $.each($data,function(i,v){
                $('select[name=new]').append('<option value="'+ i +'"> '+ v +'</option>');
            }); // end each

        } // callback
    ); // $.post
}