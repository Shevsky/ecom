import React from 'react';
import ReactDOM from 'react-dom';
import { MainScreen } from 'modules/settings/components/screens';
import { GlobalModel, SettingsModel } from 'modules/settings/models';
import { SettingsContext } from './settings-context';
import { ParamsContext } from './params-context';
import { GlobalContext } from './global-context';

export class Constructor {
	private readonly root: HTMLElement;
	private readonly params: Omit<IParams, 'settings'>;

	private readonly settingsModel: SettingsModel;
	private readonly globalModel: GlobalModel;

	constructor(selector: string, { settings, ...params }: IParams) {
		this.root = document.querySelector(selector);
		if (!this.root) {
			throw `Не удалось инициализировать контейнер: элемент "${selector}" не найден`;
		}

		this.params = params;

		this.settingsModel = new SettingsModel(settings);
		this.globalModel = new GlobalModel(params);

		this.build();
	}

	private build() {
		ReactDOM.render(
			<ParamsContext.Provider value={this.params}>
				<SettingsContext.Provider value={this.settingsModel}>
					<GlobalContext.Provider value={this.globalModel}>
						<MainScreen />
					</GlobalContext.Provider>
				</SettingsContext.Provider>
			</ParamsContext.Provider>,
			this.root
		);
	}
}
