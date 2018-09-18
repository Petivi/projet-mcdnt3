import { PipeTransform, Pipe } from "@angular/core";

@Pipe({ name: 'filter' })
export class FilterPipe implements PipeTransform {
    transform(value: any, args1: any, args2: any): any {
        let col = null;
        if (args2 !== undefined) { col = args2['col']; }
        let filter = args1;
        if (filter && Array.isArray(value)) {
            let filterKeys = Object.keys(filter);
            let tabFilter = value.filter(item =>
                filterKeys.reduce((memo, keyName) =>
                    memo && item[keyName] === filter[keyName], true));
            if (tabFilter.length > 0) {
                if (col != null) {
                    return tabFilter[0][col];
                } else {
                    return tabFilter;
                }
            }
            return tabFilter;
        } else {
            return value;
        }
    }
}