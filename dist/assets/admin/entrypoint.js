// Start stimulus app and register LiveController
import { startStimulusApp } from '@symfony/stimulus-bridge';
import LiveController from '@symfony/ux-live-component';

const app = startStimulusApp();
app.register('live', LiveController);

// In this file you can import assets like images or stylesheets
console.log('Hello Webpack Encore! Edit me in assets/admin/entrypoint.js');
