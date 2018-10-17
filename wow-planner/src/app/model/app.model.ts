export class User {
    firstname: string;
    lastname: string;
    pseudo: string;
    mail: string;
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
    msg_fr: string;
    msg_en: string;
    page: string;
    pseudo: any;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}

export class WordSimplified {
    msg_name: string;
    value: string;
    constructor(info: any) {
        for (let k in info) {
            if (info.hasOwnProperty(k)) {
                this[k] = info[k];
            }
        }
    }
}