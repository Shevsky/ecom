const webpack = require('webpack');
const merge = require('webpack-merge');
const common = require('./webpack.config');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

module.exports = merge(common, {
	mode: 'production',
	plugins: [
		new UglifyJSPlugin({
			uglifyOptions: {
				output: {
					comments: false
				}
			}
		}),
		new webpack.DefinePlugin({
			'process.env.NODE_ENV': JSON.stringify('production')
		})
	],
	devtool: 'source-map'
});
