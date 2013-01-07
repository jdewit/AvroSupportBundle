function questionListModel(options) {
    var self = this;

    self.questions = ko.observableArray(options ? options.questions : []);
    if (self.questions().length === 1) {
        avro.questionModel.setquestion(self.questions()[0]);
    }

    self.checkAll = ko.observable(false);
    self.filter = ko.observable('Active');
    self.orderBy = ko.observable();
    self.direction = ko.observable();
    self.offset = ko.observable();
    self.limit = ko.observable();

    self.resetSearchForm = function() {
        self.orderBy('updatedAt');
        self.direction('ASC');
        self.offset(0);
        self.limit(15);
    }
    self.resetSearchForm();

    self.dialogQueue = $({}); 

    self.refreshList = function() {
        if (self.dialogQueue.queue('dialogs').length === 0) {
            avro.ajaxManager.clearCache();
            self.offset.valueHasMutated();
        }
    }

    self.newQuestion = function(data, event) { 
        avro.questionModel.setQuestion(null); 
    };
    self.editQuestion = function(data, event) { 
        avro.questionModel.setQuestion(data); 
    };

    self.batchEdit = function(data, event) {
        var checked = $('input.selector:checked');
        if (checked.length) {
            if (confirm('Are you sure you want to edit ' + checked.length  + ' question' + (checked.length > 1 ? 's' : '') + '?')) {
                $.each(checked, function() {
                    var id = $(this).val();
                    self.dialogQueue.queue('dialogs', function(next) {
                        question = ko.utils.arrayFirst(self.questions(), function(question) {
                            if (question.id == id) {
                                return true;
                            }
                        });
                        if (question) {
                            avro.questionModel.setQuestion(question);
                            $('#questionFormModal').on('hidden', function() {
                            $('input#selector-' + question.id).attr('checked', false);
                                next();       
                            });
                        } else {
                            next();
                        }
                    });
                });        
                self.dialogQueue.dequeue('dialogs');
            }
        } else {
            avro.createNotice('No questions were selected');
        }
    };
    self.deleteQuestion = function(data, event) { 
        var target = event.currentTarget; 
        var href = target.href; 

        if (confirm("Are you sure you want to delete this Question?")) {
            var target = event.currentTarget;
            var href = target.href; 

            avro.ajax({
                url: href,
                success: function(answer){
                    if (answer['status'] === 'OK') {
                        self.questions.remove(function(question) { return question.id == answer['data'] });
                        avro.createSuccess(answer['notice']);
                        self.refreshList();
                        $('#questionFormModal').modal('hide');
                    } else {
                        avro.createError(answer['notice']);
                    }
                },
            });
        }
    };
    self.batchDelete = function(data, event) {
        var checked = $('input.selector:checked');
        if (checked.length) {
            if (confirm("Are you sure you want to delete these  questions?")) {
                avro.ajax({
                    url: event.currentTarget.href,
                    data: checked,
                    success: function(answer){
                        if (answer['status'] === 'OK') {
                            self.refreshList();
                            avro.createSuccess(answer['notice']);
                        } else {
                            avro.createError(answer['notice']);
                        }
                    }
                });
            }
        } else {
            avro.createNotice('No  questions selected');
        }
    };
    self.restoreQuestion = function(data, event) { 
        var target = event.currentTarget; 
        var href = target.href; 

        avro.ajax({
            url: href,
            success: function(answer){
                if (answer['status'] === 'OK') {
                    self.questions.remove(function(question) { return question.id == answer['data'] });
                    avro.createSuccess(answer['notice']);
                    self.refreshList();
                    $('#questionFormModal').modal('hide');
                } else {
                    avro.createError(answer['notice']);
                }
            }
        });
    };
    self.batchRestore = function(data, event) {
        var checked = $('input.selector:checked');
        if (checked.length) {
            if (confirm("Are you sure you want to restore these  questions?")) {
                href = event.currentTarget.href; 
                avro.ajax({
                    url: href,
                    data: checked,
                    success: function(answer){
                        if (answer['status'] === 'OK') { 
                            avro.createSuccess(answer['notice']);
                            self.refreshList();
                        } else {
                            avro.createError(answer['notice']);
                        }
                    }
                });
            }
        } else {
            avro.createNotice('No  questions selected');
        }
    };

}

