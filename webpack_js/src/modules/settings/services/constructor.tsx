import React from 'react';
import ReactDOM from 'react-dom';
import { MainScreen } from 'modules/settings/components/main-screen';
import { SettingsContext } from './settings-context';
import { SettingsModel } from 'modules/settings/models';
import { ParamsContext } from './params-context';

export class Constructor {
	private readonly root: HTMLElement;
	private readonly params: Omit<IParams, 'settings'>;
	private readonly settingsModel: SettingsModel;

	constructor(selector: string, { settings, ...params }: IParams) {
		this.root = document.querySelector(selector);
		if (!this.root) {
			throw `Не удалось инициализировать контейнер: элемент "${selector}" не найден`;
		}

		this.params = params;

		this.settingsModel = new SettingsModel(settings);

		this.build();
	}

	private build() {
		ReactDOM.render(
			<SettingsContext.Provider value={this.settingsModel}>
				<ParamsContext.Provider value={this.params}>
					<MainScreen />
				</ParamsContext.Provider>
			</SettingsContext.Provider>,
			this.root
		);
	}
}
