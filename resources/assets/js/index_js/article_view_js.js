$(document).ready(function() {
    $("body").show();

    $(".content").css("height", $(window).height() - $(".menu").height() - 5);
    $(".article_title").css("width", $(".content").width());
});

var article_view = new Vue({
    el: '.content',
    data: {
        show: true,
        opinion_data: "",
        article_unqid: "",
    },
    methods: {
        to_edit_page: function(data) {
            // this.edit_data = data;
            // console.log(this.edit_data);
            window.location = '/KMS/public/article_edit_data' + data;
        },
        show_click: function(unqid) {
            this.article_unqid = unqid;
            if (this.show == true) {
                let self = this;
                axios.get('data/get_opinion_data/' + unqid)
                    .then(function(response) {
                        self.opinion_data = response.data;
                        console.log(self.opinion_data);
                    })
                    .catch(function(response) {
                        console.log("error");
                    });
            }
            this.show = !this.show;
        },
        sent_opinion: function() {
            console.log(this.$refs.comments_textarea.value);
            let self = this;
            axios.post('post/create_new_opinion', {
                    opinion_content: this.$refs.comments_textarea.value,
                    article_unqid: this.$refs.article_unqid.value,
                    best: 0,
                    bad: 0,
                })
                .then(function(response) {
                    console.log(response.data);
                    self.get_opinion_data();
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        get_opinion_data: function() {
            let self = this;
            axios.get('data/get_opinion_data/' + this.article_unqid)
                .then(function(response) {
                    self.opinion_data = response.data;
                    console.log(self.opinion_data);
                })
                .catch(function(response) {
                    console.log("error");
                });
        },

    },
});