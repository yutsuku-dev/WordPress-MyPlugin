class Render
{
    getType(obj)
    {
        const result = ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase();
        return result;
    }

    /**
     * @param {object} item 
     */
    fromObject(item, level = 0)
    {
        let markup = '';
        Object.entries(item).forEach(([key, value]) => {
            markup += `<div class="property level-${level}"><b>${key}</b>:`;
            markup += this.fromSimple(value, level+1);
            markup += `</div>`;
        });

        return markup;
    }

    /**
     * @param {array} item 
     */
    fromArray(item, level = 0)
    {
        let markup = '';
        item.forEach((value, key) => {
            markup += `<div class="key level-${level}"><b>${key}</b>`;
            markup += this.fromSimple(value, level+1);
            markup += `</div>`;
        });

        return markup;
    }

    fromSimple(item, level = 0)
    {
        let markup = '';
        const t = this.getType(item);

        if (t === 'number') {
            markup += `<span class="number level-${level}">${item}</span>`;
        } else if (t === 'string') {
            markup += `<span class="string level-${level}">${item}</span>`;
        } else if (t === 'boolean') {
            markup += `<span class="boolean level-${level}">${item ? 'true' : 'false'}</span>`;
        } else {
            markup += this.fromGeneric(item, level+1)
        }

        return markup;
    }

    fromGeneric(item, level = 0)
    {
        let markup = '';

        if (level === 0) {
            markup += `<div>`;
        }

        const t = this.getType(item);

        if (t === 'number') {
            markup += `<div class="number level-${level}">${item}</div>`;
        } else if (t === 'string') {
            markup += `<div class="string level-${level}">${item}</div>`;
        } else if (t === 'boolean') {
            markup += `<div class="boolean level-${level}">${item ? 'true' : 'false'}</div>`;
        } else if (t === 'array') {
            markup += `<div class="array level-${level}">`;
            markup += this.fromArray(item, level+1);
            markup += `</div>`;
        } else if (t === 'object') {
            markup += `<div class="object level-${level}">`;
            markup += this.fromObject(item, level+1);
            markup += `</div>`;
        }

        if (level === 0) {
            markup += `</div>`;
        }

        return markup;
    }

    fromOther(item, level = 0)
    {

    }
}

export { Render };
