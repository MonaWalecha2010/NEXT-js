import {isProxy, toRaw} from "vue";
import {Panelhelper} from "@/Jetworks/Panelhelper";

const Userhelper = {

    page: null,
    user : null,
    roles: null,

    initialise( page) {
        let pageObject = page;
        // IS there a way we can pass in a Vue3 usePage() object and still get the relevant data?
        //if ( isProxy (page) ){
        //    pageObject = toRaw(page);
        //}
        this.page = page;
        this.user = this.page.props.auth.user;
        this.code_expires = this.page.props.auth.code_expires;
        this.roles = this.page.props.helpers.roles;
    },

    isWorker : function() {
        return this.user.is_worker;
    },

    isAdmin : function() {
        return this.user.is_admin;
    },

    isMechanic : function() {
        return this.user.is_mechanic;
    },

    isEngineer : function() {
        return this.user.is_engineer;
    },

    isSeniorEngineer : function() {
        return this.user.is_cengineer;
    },

    isTrainingAdmin : function() {
        return this.user.is_training_admin;
    },

    isTrainingOfficer : function() {
        return this.user.is_training_officer;
    },

    isTrainingManager : function() {
        return this.user.is_training_manager;
    },

    isTrainingEngineer : function() {
        return this.user.is_training_engineer;
    },

    isTrainingMechanic : function() {
        return this.user.is_training_mechanic;
    },

    getColor : function() {
        return this.user.color;
    },

    getFullName : function() {
        return this.user.fname + " " + this.user.lname;
    },

    getRoleNameFromUser : function( user ) {
        let role = Panelhelper.getItemFromCollection( user.role_id, this.roles )
        if( role ){
            return role.name;
        }
        return null;
    },

    /**
     *
     * @param Panelhelper
     * @param step_id
     * @returns {boolean}
     */
    isWorkerAllowedToStartTask( Panelhelper, step_id ) {
        if( this.isAdmin() ){
            return false;
        }
        if( this.isMechanic() ){
            let stepLabel = Panelhelper.getStepName( step_id, Panelhelper.steps);
            if( stepLabel.indexOf('Inspect') != -1 ){
                return false;
            }
        }
        return true;
    }
}
export {Userhelper};
