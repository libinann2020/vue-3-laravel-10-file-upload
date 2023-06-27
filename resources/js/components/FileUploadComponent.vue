<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-headr">File upload using Laravel 10 and VUE JS 3</div>
                <div class="card-body">
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" v-model="title" id="title" required />
                        <label for="title">File Title</label>
                        <span class="text-danger">{{ taskErrors.title }}</span>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="file" class="form-control" v-on:change="changeFile" name="file" id="file" />
                        <span class="text-danger">{{ taskErrors.file }}</span>
                    </div>
                    <button type="button" @click="uploadFile" class="btn btn-success">Upload File</button>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info">
        {{ this.successMsg }}
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="table-info">
                    <th colspan="5" class="text-center">All files</th>
                </tr>
                <tr>
                    <th scope="col">File Title</th>
                    <th scope="col">File Path</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="file in files">
                    <td>{{ file.title }}</td>
                    <td>{{ file.file_path }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title:'',
            file:'',
            successMsg:'',
            files:{},
            taskErrors: {
                title:'',
                file:''
            }
        }
    },
    methods: {
        changeFile(e) {
            this.file = e.target.files[0];
        },
        getFiles(){
            axios.get('getFiles')
            .then(response => {
                this.files = response.data; 
            }).catch(errors => {
                console.log(errors)
            })
        },
        uploadFile(){
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData;
            data.append('title', this.title)
            data.append('file', this.file)
            axios.post('saveFile',data,config)
            .then(response => {
                this.getFiles();
                this.successMsg = 'File uploaded successfully';
            }).catch(errors => {
                console.log(errors)
                if(errors.response.data.title){
                    this.taskErrors.title = errors.response.data.title[0];
                }
                if(errors.response.data.file){
                    this.taskErrors.file = errors.response.data.file[0];
                }
            })
        }
    },
    mounted() {
        this.getFiles();
    }
}
</script>