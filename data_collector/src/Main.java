

public class Main {

    public static void main(String[] args) {

        // Create a instance of the DataCollector class with port 3307
        DataCollector dc = new DataCollector(3307);

        // Start listening to incoming messages from clients (loops forever)
        dc.Listen();

    }
}
