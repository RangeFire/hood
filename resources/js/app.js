require ('./lib/bootstrap.bundle.min.js');

/* Lib classes */
import * as $ from 'jquery';
import * as Cropper from './lib/cropper.min.js';
import * as SlimSelect from './lib/slimselect.min.js';
import * as dataTables from './lib/jquery.dataTables.min.js';
import * as axios from 'axios';
import * as Quill from 'quill';
import { EmojiButton } from '@joeattardi/emoji-button';
import { Chart, registerables } from 'chart.js';
import barba from '@barba/core';
import dateJS from 'datejs';
Chart.register(...registerables);
import * as Emoji from "./lib/quill-emoji";
// Quill.register("modules/emoji", Emoji);

/* creates window link from lib classes */
window.$ = $;
window.Cropper = Cropper;
window.SlimSelect = SlimSelect;
window.dataTable = dataTables;
window.axios = axios;
window.Quill = Quill;
window.EmojiButton = EmojiButton;
window.Chart = Chart;
// window.Emoji = Emoji;
window.dateJS = dateJS;
window.barba = barba;


/* Custom classes */
// import { API/*, System, ToolTrack*/ } from './custom/api.js';
import { Notification } from './custom/notification.js';
import { CustomUpload } from './custom/customUpload.js';
import { customCheckBox } from './custom/customCheckBox.js';
import { CircularProgress } from './custom/circularProgress.js';

/* creates window link from custom classes */
// window.API = API;
// window.ToolTrack = ToolTrack;
// window.System = System;
window.Notification = Notification;
window.CustomUpload = CustomUpload;
window.customCheckBox = customCheckBox;
window.CircularProgress = CircularProgress;