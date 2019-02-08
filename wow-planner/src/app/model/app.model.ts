export class User {
    firstname: string;
    lastname: string;
    pseudo: string;
    mail: string;
    login: string;
    password: string;
    id: string;
    session_token: string;
    active_account: string;
    libelle_active_account: string;
    checked_mail: string;
    libelle_checked_mail: string;
    created_date: string;
    last_connection: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class Word {
    msg_name: string;
    value: string;
    msg_fr: string;
    msg_en: string;
    page: string;
    pseudo: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class Requete {
    id: string;
    user_mail: string;
    request_date: string;
    request_subject: string;
    request_text: string;
    request_closed: string;
    request_ref: string;
    libelle_request_closed: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class LogUser {
    id: string;
    request_date: string;
    request_token: string;
    request_type: string;
    user_firstname: string;
    user_id: string;
    user_lastname: string;
    user_mail: string;
    user_pseudo: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class LogUserBlocked {
    id: string;
    user_id: string;
    user_ip: string;
    date_blocked: string;
    date_unblocked: string;
    login_tried: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class LogUserManagement {
    id: string;
    action: string;
    comment: string;
    date_action: string;
    user_id: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class RecherchCreationPersonnage {
    quality: number;
    lvlMin: number;
    lvlMax: number;
    matiere: number;
    name: string;
    class: number;
    inventoryType: number;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class Character {
    id: string;
    race_id: number;
    class_id: number;
    head_id: number;
    neck_id: number;
    shoulder_id: number;
    chest_id: number;
    waist_id: number;
    legs_id: number;
    feet_id: number;
    wrist_id: number;
    hands_id: number;
    finger1_id: number;
    finger2_id: number;
    trinket1_id: number;
    trinket2_id: number;
    back_id: number;
    main_hand_id: number;
    off_hand_id: number;
    attack: number;
    armour: number;
    stamina: number;
    health: number;
    critical_strike: number;
    haste: number;
    mastery: number;
    versatility: number;

    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class Item {
    affichage: number;
    armor: number;
    artifactId: number;
    availableContexts: string[];
    baseArmor: number;
    bonusLists: number[];
    bonusStats: any[];
    bonusSummary: any;
    buyPrice: number;
    containerSlots: number;
    context: string;
    description: string;
    disenchantingSkillRank: number;
    displayInfoId: number;
    equippable: boolean;
    hasSockets: boolean;
    heroicTooltip: boolean;
    icon: string;
    id: number;
    inventoryType: number;
    isAuctionable: boolean;
    itemBind: number;
    itemClass: number;
    itemLevel: number;
    itemSource: any;
    itemSpells: any[];
    itemSubClass: number;
    item_allowable_classes: number[];
    item_allowable_races: number[];
    item_class: string;
    item_icon: string;
    item_id: string;
    item_inventory_type: string;
    item_name: string;
    item_quality: string;
    item_required_level: number;
    item_subclass: string;
    maxCount: number;
    maxDurability: number;
    minFactionId: number;
    minReputation: number;
    name: string;
    nameDescription: string;
    nameDescriptionColor: string;
    quality: number;
    qualityName: string;
    requiredLevel: number;
    requiredSkill: number;
    requiredSkillRank: number;
    sellPrice: number;
    stackable: number;
    upgradable: boolean;
    imgUrl: string;
}