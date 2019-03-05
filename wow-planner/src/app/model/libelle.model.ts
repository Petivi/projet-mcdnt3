export class Libelle<noMes, lbMes> {
    [noMes: number]: lbMes;
}
export class Combo<name, list> {
    [name: string]: Array<any>;
}
export class ComboElement {
    cCode: string;
    cLibelle: string;
    cLibelle2: string;
    cLibelle3: string;
    cNomCombo: string;
    cParent: string;
    iSeqId: number;

    constructor(info: any) {
        this.cCode = info.cCode;
        this.cLibelle = info.cLibelle;
    }
}
