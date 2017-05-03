/* ===========================================================
 * bootstrap-fileupload.js j2
 * http://jasny.github.com/bootstrap/javascript.html#fileupload
 * ===========================================================
 * Copyright 2012 Jasny BV, Netherlands.
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

!function ($) {

    "use strict"; // jshint ;_

    /* FILEUPLOAD PUBLIC CLASS DEFINITION
     * ================================= */

    var BootFileupload = function (element, options) {
        this.$element = $(element)
        this.type = this.$element.data('uploadtype') || (this.$element.find('.thumbnail').length > 0 ? "image" : "file")

        this.$input = this.$element.find(':file')
        if (this.$input.length === 0) return

        this.name = this.$input.attr('name') || options.name

        this.$hidden = this.$element.find('input[type=hidden][name="' + this.name + '"]')
        if (this.$hidden.length === 0) {
            this.$hidden = $('<input type="hidden" />')
            this.$element.prepend(this.$hidden)
        }

        this.$preview = this.$element.find('.fileupload-preview')
        this.$filename = this.$element.find('.fileupload-filename')
        this.$filedelete = this.$element.find('.fileupload-delete')
        var height = this.$preview.css('height')
        if (this.$preview.css('display') != 'inline' && height != '0px' && height != 'none') this.$preview.css('line-height', height)

        this.original = {
            'exists': this.$element.hasClass('fileupload-exists'),
            'preview': this.$preview.html(),
            'hiddenVal': this.$hidden.val()
        }

        this.$remove = this.$element.find('[data-dismiss="fileupload"]')

        this.$element.find('[data-trigger="fileupload"]').on('click.bootfileupload', $.proxy(this.trigger, this))

        this.listen()
    }

    BootFileupload.prototype = {

        listen: function () {
            this.$input.on('change.bootfileupload', $.proxy(this.change, this))
            $(this.$input[0].form).on('reset.bootfileupload', $.proxy(this.reset, this))
            if (this.$remove) this.$remove.on('click.bootfileupload', $.proxy(this.clear, this))
        },

        change: function (e, invoked) {
            var file = e.target.files !== undefined ? e.target.files[0] : (e.target.value ? { name: e.target.value.replace(/^.+\\/, '') } : null)
            if (invoked === 'clear') return

            if (!file) {
                this.clear()
                return
            }

            this.$hidden.val('')
            this.$hidden.attr('name', '')
            this.$input.attr('name', this.name)
            this.$filedelete.val(1);

            if (this.type === "image" && this.$preview.length > 0 && (typeof file.type !== "undefined" ? file.type.match('image.*') : file.name.match('\\.(gif|png|jpe?g)$')) && typeof FileReader !== "undefined") {
                var reader = new FileReader()
                var preview = this.$preview
                var filename = this.$filename
                var element = this.$element

                reader.onload = function (e) {
                    preview.html('<img src="' + e.target.result + '" ' + (preview.css('max-height') != 'none' ? 'style="max-height: ' + preview.css('max-height') + ';"' : '') + ' />')
                    element.addClass('fileupload-exists').removeClass('fileupload-new')
                    filename.text(file.name);
                }

                reader.readAsDataURL(file)
            } else {
                this.$preview.text(file.name)
                this.$filename.text(file.name);
                this.$element.addClass('fileupload-exists').removeClass('fileupload-new')
            }
            
            this.$element.find('.fileupload-change').remove();
        },

        clear: function (e) {
            this.$hidden.val('')
            this.$hidden.attr('name', this.name)
            this.$input.attr('name', '')
            this.$filedelete.val(1);

            //ie8+ doesn't support changing the value of input with type=file so clone instead
            if ($.browser.msie) {
                var inputClone = this.$input.clone(true);
                this.$input.after(inputClone);
                this.$input.remove();
                this.$input = inputClone;
            } else {
                this.$input.val('')
            }

            this.$preview.html('')
            this.$filename.html('')
            this.$element.addClass('fileupload-new').removeClass('fileupload-exists')

            if (e) {
                this.$input.trigger('change', [ 'clear' ])
                e.preventDefault()
            }
        },

        reset: function (e) {
            this.clear()

            this.$hidden.val(this.original.hiddenVal)
            this.$preview.html(this.original.preview)

            if (this.original.exists) this.$element.addClass('fileupload-exists').removeClass('fileupload-new')
            else this.$element.addClass('fileupload-new').removeClass('fileupload-exists')
        },

        trigger: function (e) {
            this.$input.trigger('click')
            e.preventDefault()
        }
    }


    /* FILEUPLOAD PLUGIN DEFINITION
     * =========================== */

    $.fn.bootfileupload = function (options) {
        return this.each(function () {
            var $this = $(this)
                , data = $this.data('fileupload')
            if (!data) $this.data('fileupload', (data = new BootFileupload(this, options)))
            if (typeof options == 'string') data[options]()
        })
    }

    $.fn.bootfileupload.Constructor = BootFileupload


    /* FILEUPLOAD DATA-API
     * ================== */

    $(function () {
        $('body').on('click.bootfileupload.data-api', '[data-provides="fileupload"]', function (e) {
            var $this = $(this)
            if ($this.data('fileupload')) return
            $this.bootfileupload($this.data())

            var $target = $(e.target).is('[data-dismiss=fileupload],[data-trigger=fileupload]') ?
                $(e.target) : $(e.target).parents('[data-dismiss=fileupload],[data-trigger=fileupload]').first()
            if ($target.length > 0) {
                $target.trigger('click.bootfileupload')
                e.preventDefault()
            }
        })
    })

}(window.jQuery);