class MyPlugin {
    /** @type {string} */
    endpoint;
    /** @type {Element} */
    view;
    /** @type {Element} */
    viewDetails;
    /** @type {string} */
    baseUrl;
    /** @type {string} */
    slug = '/my-lovely-users-table/';

    /**
     * @param {Render} renderer
     */
    constructor(renderer) {
        const endpoint_wp = document.querySelector('link[rel="https://api.w.org/"]').href
        this.endpoint = endpoint_wp + 'Yutsuku/WordPress/MyPlugin/v1/users';
        this.view = document.querySelector('#my-plugin');
        this.viewDetails = document.querySelector('#my-plugin-user-view');
        this.baseUrl = window.location.origin + this.slug;
        this.renderer = renderer;
    }

    /**
     * @returns {Promise.<object>}
     */
    async users() {
        const response = await fetch(this.endpoint);
        const data = await response.json();

        return data;
    }

    /**
     * @param {Number} id
     * @returns {Promise.<object>}
     */
    async user(id) {
        const response = await fetch(this.endpoint + '/' + id);
        const data = await response.json();

        return data;
    }

    /**
     * @param {Array} users An users array obtained from `users` method
     * @returns {String}
     */
    template_users(users) {
        return `
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Username</th>
            </tr>
            ${users.map(entry => {
                const endpoint_entry = `${this.slug}${entry.id}/`;
                return `
                <tr data-myplugin-row data-id="${entry.id}">
                    <td><a data-myplugin-link data-id="${entry.id}" href="${endpoint_entry}">${entry.id}</a></td>
                    <td><a data-myplugin-link data-id="${entry.id}" href="${endpoint_entry}">${entry.name}</a></td>
                    <td><a data-myplugin-link data-id="${entry.id}" href="${endpoint_entry}">${entry.username}</a></td>
                </tr>
                `;
            }).join('')}
        </table>
        `;
    }

    /**
     * @param {Number} id 
     */
    markActiveRow(id) {
        const rows = document.querySelectorAll(`[data-myplugin-row]`);
        const active_row = document.querySelector(`[data-myplugin-row][data-id="${id}"]`);

        rows.forEach(element => element.classList.remove('active'));
        active_row.classList.add('active');
    }

    /**
     * @param {Element} element 
     */
    bindUsersLinks(element) {
        element.querySelectorAll('[data-myplugin-link]').forEach(e => {
            e.addEventListener('click', async function (event) {
                event.preventDefault();
                history.replaceState(
                    {},
                    '',
                    this.baseUrl + event.target.dataset.id + '/'
                );

                this.markActiveRow(event.target.dataset.id);

                this.render_user(null, true);
                const details = await this.user(event.target.dataset.id);
                this.render_user(details);
                
            }.bind(this), false);
        });
    }

    async render_users() {
        const users = await this.users();
        this.view.innerHTML = this.template_users(users.elements);
        this.bindUsersLinks(this.view);
    }

    /**
     * @param {object} user An user object obtained from `user` method
     * @param {boolean} clear Resets the view
     * @returns 
     */
    render_user(user, clear = false) {
        if (clear) {
            this.viewDetails.innerHTML = '';
            return;
        }

        const result = this.renderer.fromGeneric(user);
        //this.viewDetails.innerHTML = JSON.stringify(user, null, 4);
        this.viewDetails.innerHTML = result;
    }

    async render() {
        await this.render_users();

        const result = window.location.pathname.match(`${this.slug}(?<id>[0-9]+)`);
        if (result?.groups?.id) {
            const user = await this.user(result.groups.id);
            this.markActiveRow(result.groups.id);
            this.render_user(user);
        }
    }
}

export { MyPlugin };
