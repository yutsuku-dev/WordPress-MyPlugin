'use strict';

import { MyPlugin } from './modules/MyPlugin.js';
import { Render } from './modules/Render.js'

const render = new Render();
const myPlugin = new MyPlugin(render);
myPlugin.render();
