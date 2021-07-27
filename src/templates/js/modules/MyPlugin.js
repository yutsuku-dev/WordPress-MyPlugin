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

        if (!response.ok) {
            return null;
        }

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
     * @returns {String}
     */
    template_error(details) {
        return `
            <div data-myplugin-error>
                <h3>Looks like there was an error :(</h3>
                <h4>${details}</h4>
            </div>
        `;
    }

    /**
     * @returns {String}
     */
    template_error_users() {
        return this.template_error('There are no users or we failed fetching them');
    }

    /**
     * @returns {String}
     */
    template_error_user() {
        return this.template_error('This user does not exist or we failed to load data for that user');
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

        if (!users || users.length === 0) {
            this.view.innerHTML = this.template_error_users();
            return;
        }

        this.view.innerHTML = this.template_users(users);
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

            if (!user) {
                this.viewDetails.innerHTML = this.template_error_user();
                return;
            }

            this.markActiveRow(result.groups.id);
            this.render_user(user);
        }
    }
}

export { MyPlugin };
