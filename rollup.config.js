import typescript from '@rollup/plugin-typescript'
import { nodeResolve } from '@rollup/plugin-node-resolve'
import commonjs from '@rollup/plugin-commonjs'
import { terser } from 'rollup-plugin-terser'

const plugins = [
    // Finds node modules
    nodeResolve({
        preferBuiltins: true,
        browser: true
    }),
    // Converts CommonJS modules to ES Modules
    commonjs(),
    typescript({
        tsconfig: './tsconfig.json'
    })
    // Minification
    //terser()
]

export default [
    {
        input: ['./client/main.ts'],
        output: {
            dir: './public/js',
            format: 'es'
        },
        plugins
    },
    {
        input: ['./client/pages/login.ts'],
        output: {
            dir: './public/js/pages',
            format: 'es'
        },
        plugins
    }
]
