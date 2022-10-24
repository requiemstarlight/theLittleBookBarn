const Confirm = {
    open (options) {
        options = Object.assign({}, {
            title: '',
            message: '',
            okText: "OK",
            cancelText: "Cancel",
            onok: function () {},
            oncancel: function () {}
        }, options);

        const html = `
        <div class="confirm">
        <div class="confirm__window">
            <div class="confirm__titlebar">
                <span class="confirm__title">
                    ${options.title}
                </span>
                <button class="confirm__close">&times;</button>
                </div>
                <div class="confirm__content">
                    ${options.message}
                </div>
                <div class="confirm__buttons">
                    <button class="confirm__button confirm__button--ok confirm__button--fill">
                    ${options.okText}
                    </button>
                    <button class="confirm__button confirm__button--cancel">
                    ${options.cancelText}
                    </button>
                </div>
            </div>
        </div>
        `;

        const template = document.createElement('template');

        template.innerHTML = html;

        const confirmElement = template.content.querySelector(".confirm");
        const btnClose = template.content.querySelector(".confirm__close");
        const btnOk = template.content.querySelector(".confirm__button--ok");
        const btnCancel = template.content.querySelector(".confirm__button--cancel");

        confirmElement.addEventListener('click', (e) => {
            if(e.target === confirmElement) {
                options.oncancel();
                this._close(confirmElement);
            }  
        });

        btnOk.addEventListener('click', () => {
                options.onok();
                this._close(confirmElement);          
        });

        [btnCancel, btnClose].forEach(btn => {
            btn.addEventListener('click', () => {
                options.oncancel();
                this._close(confirmElement);
            });

        });

        document.body.appendChild(template.content);
    },

    _close (confirmElement) {
        confirmElement.classList.add("confirm--close");

        // listen for the end of animation before closing the elements
        confirmElement.addEventListener('animationend', () => {
            document.body.removeChild(confirmElement);
        })
    }
};