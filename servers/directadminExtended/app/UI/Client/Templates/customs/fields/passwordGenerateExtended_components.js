
mgJsComponentHandler.addDefaultComponent('mg-pass-gen-ext', {
    template: '#t-mg-pass-gen-ext',
    props: [
        'component_id',
        'component_namespace',
        'component_index',
        'component_default_p'
    ],
    data : function() {
        return {
            password: null,
            styleClass: null
        }
    },
    mounted: function () {
        var self = this;
        self.password = self.component_default_p;
    },
    watch: {
        password:  function (newData, oldData) {
            this.calculatePasswordStr(newData, oldData)
        }
    },
    methods: {
        generateExtendedPass: function() {
            var self = this;
            var password = self.generatePassword(12);

            if(self.hasNumber(password) === false){
                password += self.generateRandNumber();
            }

            self.password =  password;
        },
        generatePassword: function(length){
            if (!length) {
                length = 6;
            }

            var charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^*()",
                password = "";

            for (var i = 0, n = charset.length; i < length; ++i) {
                password += charset.charAt(Math.floor(Math.random() * n));
            }

            return password;
        },
        calculatePasswordStr: function(newData, oldData)
        {
            if(oldData == null && newData == ""){
                this.styleClass = null;
                return true;
            }
            else
            {
                var result = zxcvbn(newData);
                var score = parseInt(result.score);
                var score_to_color = ['error', 'danger', 'warning', 'correct', 'success'];
                this.styleClass = 'password-strength--' + score_to_color[score];
            }
        },
        hasNumber: function(passwordString){
            return /\d/.test(passwordString);
        },
        generateRandNumber: function()
        {
            var numbers = "0123456789"
            return numbers.charAt(Math.floor(Math.random() * numbers.length));
        }
    }
});

