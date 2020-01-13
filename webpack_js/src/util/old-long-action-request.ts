import { Observable, Subject } from 'rxjs';
import { filter, map } from 'rxjs/operators';

export type TLongActionResponse<TStep, TResponse extends object> = TResponse & {
	process_id: string;
	step: TStep;
	is_done: boolean;
	ready?: boolean;
	is_error?: boolean;
};

export class LongActionRequest<TStep extends string, TResponse extends object = {}> {
	private readonly url: string;
	private readonly runnerParams: any = {};
	private readonly timeout: number = 25;
	private readonly requestsLimit: number = 2;
	private readonly processTimeoutDelay: number = 2000;
	private processTimeout: number;
	private finishingTimeout: number;
	private requestsCount: number = 0;

	private response = null;
	private request: {
		[requestId: string]: JQuery.jqXHR | null;
	} = {};
	private runner: JQuery.jqXHR | null = null;
	private finisher: JQuery.jqXHR = null;
	private isFinishing = false;

	startTap$: Subject<TLongActionResponse<TStep, TResponse>> = new Subject();
	stepTap$: Subject<TLongActionResponse<TStep, TResponse>> = new Subject();
	finishTap$: Subject<TLongActionResponse<TStep, TResponse>> = new Subject();

	lastResponse?: TLongActionResponse<TStep, TResponse>;
	response$: Subject<TLongActionResponse<TStep, TResponse> | null> = new Subject();
	step$: Observable<TStep> = this.response$.pipe(
		filter(Boolean),
		map((response: TLongActionResponse<TStep, TResponse>): TStep => response.step)
	);

	constructor(props: {
		url: string;
		runnerParams?: any;
		requestsLimit?: number;
		processTimeout?: number;
		timeout?: number;
	}) {
		const {
			url,
			runnerParams = {},
			requestsLimit = 2,
			processTimeout = 2000,
			timeout = 25
		} = props;

		this.url = url;
		this.runnerParams = runnerParams;
		this.requestsLimit = requestsLimit;
		this.processTimeoutDelay = processTimeout;
		this.timeout = timeout;
	}

	get processId(): string {
		return this.response.process_id;
	}

	run() {
		this.runner = $.post(
			this.url,
			this.runnerParams,
			(response: TLongActionResponse<TStep, TResponse>) => {
				console.log('Runner executed');

				if (response && response.process_id && !response.is_done) {
					this.startTap$.next(response);

					this.updateResponse(response);
					this.lastResponse = response;

					this.process();
				} else {
					this.forceFinish(response);
				}
			},
			'json'
		);
	}

	process() {
		if (!this.response || this.isFinishing) {
			return;
		}

		this.processTimeout = setTimeout(() => {
			this.process();
		}, this.processTimeoutDelay);

		if (this.requestsLimit <= this.requestsCount) {
			return;
		}

		this.requestsCount++;

		this.request[this.requestsCount] = $.post(
			this.url,
			{
				processId: this.processId
			},
			(response: TLongActionResponse<TStep, TResponse>) => {
				console.log('Step executed');

				this.request[this.requestsCount] = null;

				this.requestsCount--;

				this.updateResponse(response);

				if (!response || ((response && response.is_done) || response.ready)) {
					const isGoToFinish =
						!this.isFinishing || (this.isFinishing && this.finishingTimeout);

					if (isGoToFinish) {
						this.finish();
					}

					return;
				}

				if (response) {
					this.lastResponse = response;

					if (response.is_error) {
						this.requestsCount--;
						this.forceFinish(response);
					} else {
						this.stepTap$.next(response);
					}
				}
			},
			'json'
		);
	}

	updateResponse(response: TLongActionResponse<TStep, TResponse> | null) {
		this.response = response;

		if (!response) {
			return;
		}

		this.response$.next(response);
	}

	cancel() {
		if (this.runner !== null) {
			this.runner.abort();
			this.runner = null;
		}

		Object.keys(this.request).map((key: string) => {
			if (this.request[key] !== null) {
				this.request[key].abort();
				this.request[key] = null;
			}
		});

		if (this.finisher !== null) {
			this.finisher.abort();
			this.finisher = null;
		}

		clearTimeout(this.processTimeout);

		clearTimeout(this.finishingTimeout);

		this.forceFinish(this.response);
	}

	finish() {
		this.isFinishing = true;

		clearTimeout(this.processTimeout);

		clearTimeout(this.finishingTimeout);

		this.runFinish();
		// if (!this.response.is_reader_exists) {
		// 	this.finishingTimeout = setTimeout(() => {
		// 		this.runFinish();
		// 	}, this.processTimeoutDelay * 2);
		// } else {
		// 	clearTimeout(this.finishingTimeout);
		//
		// 	this.runFinish();
		// }
	}

	runFinish() {
		this.finishTap$.next(this.response);

		this.updateResponse(null);
	}

	forceFinish(response: TLongActionResponse<TStep, TResponse>) {
		this.updateResponse(null);

		this.finishTap$.next(response);
	}
}
