const path = require('path');
const autoprefixer = require('autoprefixer');
const Extracter = require('mini-css-extract-plugin');

const extract = new Extracter({
	filename: 'css/[name].css'
});

module.exports = {
	stats: { children: false },
	mode: 'development',
	entry: {
		settings: './src/settings.ts',
	},
	output: {
		path: path.resolve(__dirname, '../'),
		filename: 'js/[name].js'
	},
	module: {
		rules: [
			{
				test: /\.tsx?$/,
				loader: ['awesome-typescript-loader', 'tslint-loader']
			},
			{
				test: /\.(sass|scss)$/,
				use: [
					{
						loader: Extracter.loader
					},
					{
						loader: 'css-loader',
						options: {
							modules: true,
							camelCase: 'dashes',
							localIdentName: 'ecom-[local]'
						}
					},
					{
						loader: 'postcss-loader',
						options: {
							sourceMap: true,
							plugins: loader => [autoprefixer()]
						}
					},
					{
						loader: 'sass-loader'
					}
				],
				exclude: /node_modules|vendor/
			},
			{
				test: /\.css$/,
				use: [
					{
						loader: Extracter.loader
					},
					{
						loader: 'css-loader'
					}
				],
				include: /node_modules|vendor/
			},
			{
				test: /\.(png|jpg|gif|svg)$/,
				loader: 'file-loader',
				options: {
					name: '[name].[ext]',
					outputPath: 'img/',
					publicPath: (url, resourcePath, context) => {
						if (/jsx-images/.test(resourcePath)) {
							return `/img/${url}`;
						}

						return `../img/${url}`;
					}
				}
			}
		]
	},
	plugins: [extract],
	resolve: {
		extensions: ['.js', '.json', '.ts', '.tsx'],
		modules: [
			path.resolve(__dirname, 'src'),
			'node_modules'
		],
	},
	devtool: 'cheap-module-inline-source-map'
};
