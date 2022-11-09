function addDeleteEdit(type,token,functionName,string){
    return `<td>
                        <a href="/${string}/${type.id}/edit" class="text-decoration-none text-dark">
                            <i class="fa fa-edit fa-2x"></i>
                        </a>
                   </td>
                   <td>
                        <form id="delete-form-${type.id}" action="/${string}/${type.id}" method="POST">
                            <input type="hidden" name="_token" value=${token}>
                            <input type="hidden" name="_method" value="DELETE">
                            <a style="cursor: pointer;" class="text-decoration-none text-dark" onclick="${functionName}(${type.id})">
                                <i class="fa fa-trash fa-2x"></i>
                            </a>
                        </form>
                    </td>`;
}

function addShared(type,string){
    return `<td>
                <a href="${string}s/share-${string}/${type.id}" class="btn btn-sm btn-warning">
                    Share ${string}
                </a>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#sharedListModal" onclick="getSetData(${type.id},'${string}')">
                    See list
                </button>
            </td>`;
}

async function loadRest(id,level){
    let loadBtn=document.getElementById('load-rest-'+id);
    let increment = level * 20;
    let response = await fetch('/get-subfiles/' + id);
    let responseJSON = await response.json();
    if(loadBtn.classList.contains('loaded')){
        loadBtn.classList.add('not-loaded');
        loadBtn.classList.remove('loaded');
        loadBtn.innerHTML=`<i class="fa fa-angle-right"></i>`;
        responseJSON['folders'].forEach(folder => {
            let loadBtnNew=document.getElementById('load-rest-'+folder.id);
            if(loadBtnNew.classList.contains('loaded')) loadRest(folder.id,level)
            document.getElementById('row'+folder.id).classList.add('d-none');
        });
        responseJSON['files'].forEach(file => {
            document.getElementById('file-row'+file.id).classList.add('d-none');
        });
    }else {
        let newData ='';
        responseJSON['folders'].forEach(folder => {
            newData += `<tr id="row${folder.id}">
                                    ${addDeleteEdit(folder,csrf,"deleteFolder",'folders')}
                                    <td>
                                        <a class="text-decoration-none text-dark" href="/folders/${folder.id}">
                                            <i class="fa fa-plus-circle fa-2x"></i>
                                        </a>
                                    </td>
                                   <td style="padding-left: ${increment}px;">
                                        <button id="load-rest-${folder.id}" onclick="loadRest(${folder.id},${level + 1})" class="border-0 bg-white not-loaded">
                                            <i class="fa fa-angle-right"></i>
                                        </button>
                                        <i class="fa fa-folder"></i> ${folder.users_name}
                                    </td>
                                   <td style="padding-left: ${increment}px;">${folder.size}</td>
                                   <td style="padding-left: ${increment}px;">File Folder</td>
                                   ${addShared(folder,'folder')}
                                <tr>`;
        });
        responseJSON['files'].forEach(file => {
            newData += `<tr id="file-row${file.id}">
                                    ${addDeleteEdit(file,csrf,"deleteFile",'files')}
                                    <td>
                                        <a class="text-decoration-none text-dark"
                                           href="/download-file/${file.id}">
                                            <i class="fa fa-download fa-2x"></i>
                                        </a>
                                    </td>
                                   <td style="padding-left: ${increment}px;">${file.users_name}.${file.extension}</td>
                                    <td style="padding-left: ${increment}px;">${((file.size)/1024).toFixed(2)} KB</td>
                                   <td style="padding-left: ${increment}px;">${file.extension}File</td>
                                   ${addShared(file,'file')}
                                </tr>`;
        })
        loadBtn.classList.add('loaded');
        loadBtn.classList.remove('not-loaded');
        loadBtn.innerHTML = `<i class="fa fa-angle-down"></i>`;
        document.getElementById('row'+id).outerHTML=document.getElementById('row'+id).outerHTML+newData;
        increment += 20;
    }
}

function deleteFileFolder(id){
    if(confirm('Are you sure you want to delete this?'))
        document.getElementById('delete-form-'+id).submit();
}

async function getSetData(id,type){
    let modal=document.getElementById('sharedListModal');
    let response=await fetch(`/${type}s/get-share-users/${id}`);
    let data=await response.json();
    let htmlData='';
    if(data.length==0){
        htmlData=`<p class="h4 text-center">You haven't shared file with any user yet!</p>`;
        document.getElementById('table-header').classList.add('d-none');
    }else{
        document.getElementById('table-header').classList.remove('d-none');
        data.forEach((user)=>{
            htmlData+=`<tr>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>
                                    <form action="/${type}s/${id}/remove-share-${type}/${user.id}" method="POST">
                                        <input type="hidden" name="_token" value=${csrf}>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-outline-danger">Remove user</button>
                                    </form>
                                </td>
                            </tr>`;
        });
    }
    document.getElementById('list-modal-table').innerHTML=htmlData;
}
