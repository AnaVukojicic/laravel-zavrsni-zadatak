async function loadRestForShared(id,level){
    let loadBtn=document.getElementById('load-rest-'+id);
    let increment = level * 20;
    let response = await fetch('/get-shared-subfiles/' + id);
    let responseJSON = await response.json();
    if(loadBtn.classList.contains('loaded')){
        loadBtn.classList.add('not-loaded');
        loadBtn.classList.remove('loaded');
        loadBtn.innerHTML=`<i class="fa fa-angle-right"></i>`;
        responseJSON['folders'].forEach(folder => {
            let loadBtnNew=document.getElementById('load-rest-'+folder.id);
            if(loadBtnNew.classList.contains('loaded')) loadRestForShared(folder.id,level)
            document.getElementById('row'+folder.id).classList.add('d-none');
        });
        responseJSON['files'].forEach(file => {
            document.getElementById('file-row'+file.id).classList.add('d-none');
        });
    }else {
        let newData ='';
        responseJSON['folders'].forEach(folder => {
            newData += `<tr id="row${folder.id}">
                                    <td>
                                        <a class="text-decoration-none text-dark" href="/shared/show/${folder.id}">
                                            <i class="fa fa-plus-circle fa-2x"></i>
                                        </a>
                                    </td>
                                   <td style="padding-left: ${increment}px;">
                                        <button id="load-rest-${folder.id}" onclick="loadRestForShard(${folder.id},${level + 1})" class="border-0 bg-white not-loaded">
                                            <i class="fa fa-angle-right"></i>
                                        </button>
                                        <i class="fa fa-folder"></i> ${folder.users_name}
                                    </td>
                                   <td style="padding-left: ${increment}px;">File Folder</td>
                                <tr>`;
        });
        responseJSON['files'].forEach(file => {
            newData += `<tr id="file-row${file.id}">
                                    <td>
                                        <a class="text-decoration-none text-dark"
                                           href="/download-file/${file.id}">
                                            <i class="fa fa-download fa-2x"></i>
                                        </a>
                                    </td>
                                   <td style="padding-left: ${increment}px;">${file.users_name}.${file.extension}</td>
                                   <td style="padding-left: ${increment}px;">${file.extension}File</td>
                                </tr>`;
        })
        loadBtn.classList.add('loaded');
        loadBtn.classList.remove('not-loaded');
        loadBtn.innerHTML = `<i class="fa fa-angle-down"></i>`;
        document.getElementById('row'+id).outerHTML=document.getElementById('row'+id).outerHTML+newData;
        increment += 20;
    }
}
