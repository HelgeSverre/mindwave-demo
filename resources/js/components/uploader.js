export default (options = {}) => {

    // TODO(26 May 2023) ~ Helge: There is probably a more clever way to do this.
    const multiple = options?.multiple ?? false;
    const onProgress = options?.onProgress ?? null;
    const onFileCompleted = options?.onFileCompleted ?? null;
    const onFileFailed = options?.onFileFailed ?? null;
    const onAllFilesCompleted = options?.onAllFilesCompleted ?? null;
    const resetFilesOnAllFilesCompleted = options?.resetFilesOnAllFilesCompleted ?? false;
    const resetFilesOnAllFilesCompletedTimeout = options?.resetFilesOnAllFilesCompletedTimeout ?? 5000;

    return {
        counter: 1,
        dragging: false,
        multipleAllowed: multiple,
        uploadedFiles: [],

        resetFiles() {
            this.counter = 1;
            this.dragging = false;
            this.uploadedFiles = [];
        },

        onFileDropped(event) {
            this._uploadOneOrMultiple([...event.dataTransfer.files])
        },

        onFileInputChanged(event) {
            this._uploadOneOrMultiple([...event.target.files])
        },

        removeFile(index) {
            this.uploadedFiles.splice(index, 1);
        },

        getFileById(id) {
            const index = this.uploadedFiles.findIndex((f) => f.id === id);
            return this.uploadedFiles[index];
        },

        _uploadOneOrMultiple(files) {
            this.resetFiles();

            const promises = this.multipleAllowed
                ? files.map(this._uploadFile.bind(this))
                : files.slice(0, 1).map(this._uploadFile.bind(this));

            Promise.all(promises).then(() => {
                if (onAllFilesCompleted) onAllFilesCompleted(this.uploadedFiles);
                if (resetFilesOnAllFilesCompleted) setTimeout(() => this.resetFiles(), resetFilesOnAllFilesCompletedTimeout)
            });

        },

        _uploadFile(file) {
            const vm = this;

            return new Promise(function (resolve, reject) {

                let id = ++vm.counter;

                vm.uploadedFiles.push({
                    id: id,
                    progress: 0,
                    error: false,
                    name: file.name,
                    content_type: file.type,
                });

                Vapor
                    .store(file, {
                        visibility: 'private',
                        progress: progress => {
                            const index = vm.uploadedFiles.findIndex((f) => f.id === id)
                            vm.uploadedFiles[index].progress = Math.round(progress * 100);
                            vm.uploadedFiles[index].error = false;


                            if (onProgress) onProgress(progress, vm.uploadedFiles[index]);
                        }
                    })
                    .catch((err) => {
                        const index = vm.uploadedFiles.findIndex((f) => f.id === id)
                        vm.uploadedFiles[index].progress = 100;
                        vm.uploadedFiles[index].error = true;
                        if (onFileFailed) onFileFailed(vm.uploadedFiles[index]);
                        reject(err);
                    })
                    .then((response) => {
                        if (!response) return;

                        const index = vm.uploadedFiles.findIndex((f) => f.id === id)
                        vm.uploadedFiles[index] = {
                            ...vm.uploadedFiles[index],
                            uuid: response.uuid,
                            key: response.key,
                            bucket: response.bucket,
                            name: file.name,
                            content_type: file.type,
                            visibility: 'private',
                        }

                        if (onFileCompleted) onFileCompleted(vm.uploadedFiles[index]);
                        resolve(vm.uploadedFiles[index]);
                    });
            });
        },
    }

}
