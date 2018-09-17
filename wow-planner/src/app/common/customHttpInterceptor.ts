import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent, HttpResponse, HttpErrorResponse } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable()
export class CustomHttpInterceptor implements HttpInterceptor {

    intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
		if (req.responseType == 'json') {
			req = req.clone({ responseType: 'text' });

			return next.handle(req).map(response => {
				if (response instanceof HttpResponse) {
                    let tabRes: any[] = response.body.split('>');
                    let res: any = JSON.stringify({body: tabRes[tabRes.length - 1]});
					response = response.clone<any>({ body: JSON.parse(res) });
				}
				return response;
			});
		}

		return next.handle(req);
	}
}