package org.bitweaver.lucene;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

import org.apache.lucene.analysis.Analyzer;
import org.apache.lucene.analysis.standard.StandardAnalyzer;
import org.apache.lucene.document.Document;
import org.apache.lucene.document.Field;
import org.apache.lucene.index.IndexWriter;

/**
 * @author Christian Fowler <spider@bitweaver.org>
 * Based on sample code from phparch.com artilce by Dave Palmer <dave@engineworks.org>
 * in June 2003 issue.
 *
 * In order to run this, you need to first compile it. To do so, you need
 * a version of the JDK. I would recommend getting the Sun JDK 1.4:
 * http://java.sun.com
 */
public class IndexEngine {

	public static void main(String[] args) throws Exception {
		try {
			System.out.println("Preparing to index links database...");
			index(getConnection( args[0], args[1], args[2] ), args[3] );
			System.out.println("Index complete");
		} catch( ArrayIndexOutOfBoundsException e ) {
			System.out.println("Usage: javac org.bitweaver.IndexEngine <dbstring, ex: jdbc:mysql://localhost/bitweaver or jdbc:postgresql://localhost/bitweaver> <dbuser> <dbpassword> /path/to/bitweaver/temp/index");
		}
	}

	private static void index(Connection conn, String pIndexPath) throws Exception {

		// this is the query we are going to use to populate our index
		String sql = "select * from tiki_content WHERE title IS NOT NULL";

 		Analyzer analyzer = new StandardAnalyzer();
 		IndexWriter writer = new IndexWriter(pIndexPath,analyzer,true);
 		PreparedStatement pStmt = conn.prepareStatement(sql);

		System.out.println("Executing query...");

		ResultSet rs = pStmt.executeQuery();

		int count = 0;
		int interval = 250;
		long timeout = 50;

		System.out.println("Preparing to build index...");

		while (rs.next()) {
			if (count == interval) {
				java.lang.Thread.sleep(timeout);
				count = 0;
			} else {
				count++;
			}

			System.out.println("Indexing: " + rs.getString("content_id") + ": " + rs.getString("title"));

			Document d = new Document();

			// adding our columns to our Lucene Document object
			d.add(Field.Text("content_id", rs.getString("content_id")));
			d.add(Field.Text("title",rs.getString("title")));
			d.add(Field.Text("content_type_guid", rs.getString("content_type_guid")));
			if( rs.getString("data") != null ) {
				d.add(Field.Text("data", rs.getString("data")));
			}

			// adding our document object instance to our writer
			writer.addDocument(d);
		}

		writer.close();
	}

	private static Connection getConnection( String pDbConnection, String pDbUser, String pDbPass  ) throws Exception {
		// the user name and password you need to use to log in to your MySQL DB
		if( pDbConnection.indexOf( "mysql" ) > -1 ) {
			Class.forName("com.mysql.jdbc.Driver").newInstance();
		} else if( pDbConnection.indexOf( "postgresql" ) > -1 ) {
			Class.forName("org.postgresql.Driver").newInstance();
		}

		System.out.println("Preparing connection with URL: " + pDbConnection);
		System.out.println("database user: " + pDbUser);
		return DriverManager.getConnection(pDbConnection, pDbUser, pDbPass);
	}
}
