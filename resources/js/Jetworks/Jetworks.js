import {Panelhelper} from "@/Jetworks/Panelhelper";
import {Userhelper} from "@/Jetworks/Userhelper";

const Jetworks = {

    page : null,

    PanelHelper: null,

    UserHelper: null,

    initialise: function (page) {
        this.page = page;
        this.PanelHelper = Panelhelper.initialise( this.page );
        this.UserHelper = Userhelper.initialise( this.page );
    },

}

export {Jetworks};
