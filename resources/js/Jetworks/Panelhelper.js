import {isProxy, toRaw} from 'vue';

const COMPLETED_ACTION = 3;
const COMPLETED_STEP = 6;

const Panelhelper = {

    page: null,

    steps: null,

    actions: null,

    roles: null,

    user: null,

    initialise: function (page) {
        this.page = page;
        this.user = this.page.props.auth.user;
        this.steps = this.page.props.helpers.workpack_panel_steps;
        this.actions = this.page.props.helpers.workpack_panel_actions;
        this.roles = this.page.props.helpers.roles;
    },

    isNullOrUndefined(value) {
        if ((value === null) || (value === undefined)) {
            return true;
        }
        return false;
    },

    getBooleanActiveLabel(active) {
        return (active == 1) ? "Active" : 'Inactive';
    },

    getInitials: function (name) {
        let initials = [];
        let words = name.split(" ");
        for (const word of words) {
            initials.push(word.charAt(0));
        }
        return initials.join('').toUpperCase();
    },

    getUserInitials: function (user) {
        if (user.name) {
            return $this.getInitials(user.name);
        }
        return "";
    },


    getItemIndexFromCollection: function (item_id, items) {
        if (isProxy(items)) {
            items = toRaw(items);
        }
        let currentItemIndex = items.findIndex(item => item.id === item_id);
        if (currentItemIndex >= 0) {
            return currentItemIndex;
        }
        return null;
    },

    /**
     *
     * @param item_id
     * @param items
     * @returns {*|null}
     */
    getItemFromCollection: function (item_id, items) {
        if (isProxy(items)) {
            items = toRaw(items);
        }
        let currentItemIndex = this.getItemIndexFromCollection(item_id, items);
        if (currentItemIndex >= 0) {
            return items[currentItemIndex];
        }
        return null;
    },

    /**
     *
     * @param item_id
     * @param items
     * @returns {*|null}
     */
    getNextItemFromCollection: function (item_id, items) {
        let currentItemIndex = this.getItemIndexFromCollection(item_id, items);
        if (currentItemIndex !== null) {
            return items[currentItemIndex++];
        }
        return null;
    },

    /**
     *
     * @param objects
     * @param object_id
     * @param property_name
     * @returns {string}
     */
    getPropertyByIdFromObjectArray: function (objects, object_id, property_name,) {
        let value = '';
        // Sometimes data may be coming from a Vue Proxy object, so we need to convert it to a bog standard Array...
        if (isProxy(objects)) {
            objects = toRaw(objects);
        }
        let object = objects.find(object => object.id === object_id);
        if (object) {
            value = object[property_name]
        }
        return value;
    },


    /**
     *
     * @param objects
     * @param property_value
     * @param property_name
     * @returns {Object|null}
     */
    getObjectByPropertyFromObjectArray: function (objects, property_value, property_name,) {
        // Sometimes data may be coming from a Vue Proxy object, so we need to convert it to a bog standard Array...
        if (isProxy(objects)) {
            objects = toRaw(objects);
        }
        let object = objects.find(object => object[property_name] === property_value);
        if (object) {
            return object;
        }
        return null;
    },

    getActionName: function (action_id, actions) {
        if (this.isNullOrUndefined(actions)) {
            actions = this.actions;
        }
        return this.getPropertyByIdFromObjectArray(actions, action_id, 'name');
    },

    getStepName: function (step_id, steps) {
        if (this.isNullOrUndefined(steps)) {
            steps = this.steps;
        }
        return this.getPropertyByIdFromObjectArray(steps, step_id, 'name');
    },


    getStepAvailabilityLabel(panel) {
        let label = this.getActionName(panel.workpack_panel_action_id);
        if (label == "Available") {
            let stepLabel = this.getStepName(panel.workpack_panel_step_id);
            label = `${label} for ${stepLabel}`;
        }
        return label;
    },

    getStepColor: function (step_id, steps) {
        if (this.isNullOrUndefined(steps)) {
            steps = this.steps;
        }
        return this.getPropertyByIdFromObjectArray(steps, step_id, 'color');
    },

    /**
     *
     * @param workpack_panel
     * @param fade
     * @returns {string}
     */
    getStepRowbackgroundClasses: function (workpack_panel, fade = true) {
        let bgColour = `jw-bg__${this.getStepColor(workpack_panel.workpack_panel_step_id)}-lightest`;
        if (workpack_panel.completed == 1) {
            return bgColour;
        }
        if (fade) {
            if (this.isPanelStarted(workpack_panel) && this.isPanelBeingWorkedOn(workpack_panel)) {
                return bgColour + " opacity-50";
            }
        }
        if (this.isPanelStarted(workpack_panel)) {
            return bgColour;
        }
        return "";
    },

    /**
     * @param id
     * @param labels
     * @returns {*|string}
     */
    getLabelFromId: function (id, labels) {
        let value = ''
        let label = labels.find(label => label.id === id);
        if (label) {
            value = label.name;
        }
        return value;
    },

    getNextAction: function (action_id, actions) {
        if (this.isNullOrUndefined(actions)) {
            actions = this.actions;
        }
        return this.getNextItemFromCollection(action_id, actions);
    },

    getNextStep: function (step_id, steps) {
        if (this.isNullOrUndefined(steps)) {
            steps = this.steps;
        }
        return this.getNextItemFromCollection(step_id, steps);
    },

    getNextActionFromId: function (id, actions) {
        if (this.isNullOrUndefined(actions)) {
            actions = this.actions;
        }
        let value = '';
        let label = this.getLabelFromId(id, actions);
        if (label) {
            switch (label) {
                case 'Available':
                    value = 'Start';
                    break;
                case 'In Progress':
                    value = 'Finish';
                    break;
                case 'Completed':
                    value = 'Start';
                    break;
                default:
                    value = '';
            }
        }

        return value;
    },

    getNextStatusForPanel: function (panel, action_labels, status_labels) {
        if (this.isNullOrUndefined(status_labels)) {
            status_labels = this.steps;
        }
        let action_id = panel.workpack_panel_action_id;
        let status_id = panel.workpack_panel_step_id;
        let value = this.getLabelFromId(status_id, status_labels);

        //If we're on the last action we need to a new status to be returned...
        if (action_id == COMPLETED_ACTION) {
            let number_labels = status_labels.length;
            let label = status_labels.find(label => label.id === status_id);
            let new_label_id = label.id + 1;
            if (new_label_id > number_labels) {
                new_label_id = 1;
            }
            let new_label = status_labels.find(label => label.id === new_label_id);
            value = new_label.name;
        }
        return value;
    },

    getWorkerColorById: function (role_id) {
        let color = "blue";
        if (role_id == 5) {
            color = "green";
        }
        return color;
    },

    /**
     *
     * @param Object user
     */
    getWorkerColor: function (user) {
        return this.getWorkerColorById(user.role_id);
    },

    isPanelStarted: function (panel) {
        if ((parseInt(panel.workpack_panel_action_id) === 1) && (parseInt(panel.workpack_panel_step_id) === 1)) {
            return false;
        }
        return true;
    },

    isPanelBeingWorkedOn: function (panel) {
        if ((parseInt(panel.workpack_panel_action_id) == 1) || (parseInt(panel.workpack_panel_action_id) == 3)) {
            return false;
        }
        return true;
    },

    isPanelCompleted: function (panel) {
        if ((parseInt(panel.workpack_panel_step_id) == 6) && (parseInt(panel.workpack_panel_action_id) == 3)) {
            return true;
        }
        return false;
    },

    isUserWorkingOnWorkpack: function (workpackusers, user) {
        let workpackuser = workpackusers.find(workpackuser => workpackuser.id === user.id);
        if (workpackuser !== undefined) {
            return true;
        }
        return false;
    },

    isWorkpackCompleted: function (workpack) {
        if (workpack.completed == true) {
            return true;
        }
        return (workpack.workpack_stats.panels_completed == workpack.workpack_stats.panels_total);
    },

    isWorkpackClosed: function (workpack) {
        if (workpack.completed == true) {
            return true;
        }
        return false;
    }
}

export {Panelhelper};
