#!/usr/bin/env python

"""
Converts EHSP legacy MS Access database into CSV files.

Requirements: mdbtools <http://mdbtools.sourceforge.net>
"""

import os
import subprocess
import sys


class EHSP_Legacy_Data (object):
    filename = None
    
    def __init__(self):
        pass
        
    def _getTables(self):
        """ Retrieves list of tables in Access database """

        args = ['mdb-tables', '-1', self.filename]
        proc = subprocess.Popen(args, stdout=subprocess.PIPE)
        return proc.communicate()[0].split(os.linesep)[:-1]
        
    def _exportTable(self, table, directory):
        """ Exports specified table into a specified directory """

        args = ['mdb-export', self.filename, table]
        outputFilename = os.path.join(directory, '{0}.csv'.format(table))
        proc = subprocess.Popen(args, stdout=subprocess.PIPE)
        output = proc.communicate()[0]
        open(outputFilename, 'wb').write(output)
        
    def exportToCSV(self, filename, directory):
        """ Exports MS Access database, storing each table as a CSV
            in a specified directory """

        self.filename = filename

        if not os.path.exists(directory):
            os.mkdir(directory, mode=0775)
        tables = self._getTables()
        for table in tables:
            if "'" not in table:
                outputFilename = self._exportTable(table, directory)
                print('Wrote {0}'.format(outputFilename))
            
    
args = sys.argv[1:]

if __name__ == '__main__':
    if len(args) != 2:
        print('usage: {0} FILENAME OUTPUT_DIRECTORY'.format(sys.argv[0]))
        sys.exit(1)
    
    filename, outputDir = args
    
    ehsp = EHSP_Legacy_Data()
    ehsp.exportToCSV(filename, outputDir)
    