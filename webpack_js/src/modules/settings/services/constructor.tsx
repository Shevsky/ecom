import React from 'react';
import ReactDOM from 'react-dom';
import { MainScreen } from 'modules/settings/components/screens';
import { GlobalParamsModel, SettingsModel } from 'modules/settings/models';
import { SettingsContext } from './settings-context';
import { ParamsContext } from './params-context';
import { GlobalParamsContext } from './global-params-context';

export class Constructor {
	private readonly root: HTMLElement;
	private readonly params: Omit<IParams, 'settings'>;

	private readonly settingsModel: SettingsModel;
	private readonly globalParamsModel: GlobalParamsModel;

	constructor(selector: string, { settings, ...params }: IParams) {
		this.root = document.querySelector(selector);
		if (!this.root) {
			throw `Не удалось инициализировать контейнер: элемент "${selector}" не найден`;
		}

		this.params = params;

		this.settingsModel = new SettingsModel(settings);
		this.globalParamsModel = new GlobalParamsModel(params);

		this.build();
	}

	private build() {
		ReactDOM.render(
			<ParamsContext.Provider value={this.params}>
				<SettingsContext.Provider value={this.settingsModel}>
					<GlobalParamsContext.Provider value={this.globalParamsModel}>
						<MainScreen />
					</GlobalParamsContext.Provider>
				</SettingsContext.Provider>
			</ParamsContext.Provider>,
			this.root
		);
	}
}
