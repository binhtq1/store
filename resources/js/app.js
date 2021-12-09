import React from "react";
import { render } from "react-dom";
import { createInertiaApp } from "@inertiajs/inertia-react";
import { InertiaProgress } from '@inertiajs/progress'
import 'antd/dist/antd.css';

// require("./bootstrap");

InertiaProgress.init();

createInertiaApp({
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props }) {
        render(<App {...props} />, el);
    },
    // output: {
    //     chunkFilename: 'js/[name].js?id=[chunkhash]',
    // },
});
