function questionModel(options) {
    var self = this;
    self.question = options ? options.question : null,

    self.questionId = ko.observable();
    self.permalink = ko.observable();
    self.isAnswerable = ko.observable();
    self.numAnswers = ko.observable();
    self.lastAnswerAt = ko.observable();
    self.title = ko.observable();
    self.content = ko.observable();
    self.isSolved = ko.observable();
    self.solvedAt = ko.observable();
    self.isDeleted = ko.observable();
    self.modalHeading = ko.observable();
    self.closeFormModal = function() {
        $('#questionFormModal').modal('hide');
    }

    self.setQuestion = function(question) {
        $('#questionFormModal').modal('show');
        if (question) {
            self.modalHeading('Edit  Question');
            self.questionId(question.id);
            self.permalink(question.permalink);
            self.isAnswerable(question.isAnswerable);
            self.numAnswers(question.numAnswers);
            self.lastAnswerAt(question.lastAnswerAt);
            self.title(question.title);
            self.content(question.content);
            self.isSolved(question.isSolved);
            self.solvedAt(question.solvedAt);
            self.isDeleted(question.isDeleted);
        } else {
            self.modalHeading('New  Question');
            self.questionId(null);
            self.permalink(null);
            self.isAnswerable(null);
            self.numAnswers(null);
            self.lastAnswerAt(avro.getTodaysDate());
            self.title(null);
            self.content(null);
            self.isSolved(null);
            self.solvedAt(avro.getTodaysDate());
            self.isDeleted(false);
        }
    }

    self.bindForm = function(form) {
        var $form = $(form);
        $form.avroAjaxSubmit({
            success: function(answer){
                if (answer['status'] == "OK") {
                    if (answer['action'] == 'edit') {
                        avro.createNotice(answer['notice']);
                        var question = ko.utils.arrayFirst(avro.questionListModel.questions(), function(question) {
                            if (question.id == answer['data']['id']) {
                                return true;
                            }
                        });
                        avro.questionListModel.questions.replace(question, answer['data']); 
                    } else {
                        avro.questionListModel.questions.unshift(answer['data']); 
                    }
                    $('#questionFormModal').modal('hide');
                } else {
                    $.each( answer['errors'], function(field, message) {
                        $form.find('#error-container').append('<i class="sprite-error"></i> '+ message +'.').show();
                        $form.find('#avro_support_question_'+ field).closest('.control-group').addClass('error');
                    });
                }
            }
        });
    };
}


